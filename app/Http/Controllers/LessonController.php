<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use App\Http\Requests\StoreLessonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    public function create(\App\Models\Module $module)
    {
        // Mengirim data module ke view agar kita tahu materi ini masuk ke modul mana
        return view('lessons.create', compact('module'));
    }

    public function store(StoreLessonRequest $request, Module $module)
    {
        $validated = $request->validated();
        
        // Pastikan urutan (order) konsisten
        $validated['order'] = $module->lessons()->count() + 1;

        // Simpan materi
        $lesson = $module->lessons()->create($validated);

        // Arahkan ke halaman detail kursus agar user bisa melihat daftar materi yang diperbarui
        // Kita akses $module->course untuk mendapatkan slug atau ID kursus
        return redirect()->route('courses.show', $module->course->slug)
                         ->with('success', 'Materi "' . $lesson->title . '" berhasil ditambahkan!');
    }

    public function show(Lesson $lesson)
    {
        $user = Auth::user();
        
        // 1. Eager Load Module dan semua materinya untuk Sidebar
        $module = $lesson->module->load(['lessons' => function($query) {
            $query->orderBy('order', 'asc');
        }]);

        // 2. Tentukan Status Akses
        $isOwner = $module->course->user_id === $user->id;
        $isEnrolled = $user->enrolledCourses->contains($module->course_id);

        // Otorisasi: Izinkan jika dia Pemilik (Instructor) ATAU sudah Terdaftar (Student)
        if (!$isOwner && !$isEnrolled) {
            abort(403, 'Anda harus mendaftar kursus ini untuk mengakses materi.');
        }

        // 3. Logika Lock: Cek materi sebelumnya
        $prevLesson = Lesson::where('module_id', $module->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        // Syarat Lock: Bukan Owner && Ada materi sebelumnya && Materi sebelumnya belum selesai
        if (!$isOwner && $prevLesson && !$user->hasCompleted($prevLesson->id)) {
            return redirect()->route('lessons.show', $prevLesson->id)
                            ->with('error', 'Selesaikan materi sebelumnya terlebih dahulu!');
        }

        // 4. Navigasi: Cari materi berikutnya
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        // Kirim $isOwner ke view agar bisa dipakai buat nampilin badge "Mode Preview"
        return view('lessons.show', compact('lesson', 'nextLesson', 'prevLesson', 'module', 'isOwner'));
    }

    public function edit(Lesson $lesson)
    {
        // Cek otorisasi lewat Module
        // Karena Lesson punya module_id, dan Module punya course_id
        if ($lesson->module->course->user_id !== Auth::id()) {
            abort(403, 'Anda bukan pemilik kursus ini.');
        }

        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'video_url' => 'nullable|url',
        ]);

        $lesson->update($validated);

        return redirect()->route('courses.show', $lesson->module->course->slug)
                        ->with('success', 'Materi berhasil diperbarui!');
    }

    public function destroy(Lesson $lesson)
    {
        if ($lesson->course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // Hapus materi
        $lesson->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus!');
    }

    // Fungsi untuk menandai materi selesai
    public function complete(Lesson $lesson)
    {
        // Kamu bisa gunakan Auth::id() atau tetap auth()->id()
        $lesson->completedBy()->syncWithoutDetaching([
            Auth::id() => ['completed_at' => now()] 
        ]);

        return back()->with('success', 'Selamat! Materi ini selesai.');
    }
}