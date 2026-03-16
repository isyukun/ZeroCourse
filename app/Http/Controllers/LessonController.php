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
        $module = $lesson->module;

        // 1. Otorisasi: Pastikan user terdaftar di kursus ini
        if (!$user->enrolledCourses->contains($module->course_id)) {
            abort(403, 'Anda harus mendaftar kursus ini untuk mengakses materi.');
        }

        // 2. Logika Lock: Cek apakah materi ini sudah terbuka (unlocked)
        // Syarat: Materi sebelumnya harus sudah selesai (hasCompleted)
        $prevLesson = Lesson::where('module_id', $module->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($prevLesson && !$user->hasCompleted($prevLesson->id)) {
            // Tambahkan pengecekan: pastikan materi yang dituju bukan materi yang sedang diakses
            if ($lesson->id !== $prevLesson->id) {
                return redirect()->route('lessons.show', $prevLesson->id)
                                ->with('error', 'Selesaikan materi sebelumnya terlebih dahulu!');
            }
        }

        // 3. Navigasi: Cari materi berikutnya dan sebelumnya
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        return view('lessons.show', compact('lesson', 'nextLesson', 'prevLesson'));
    }

    public function edit(Lesson $lesson)
    {
        // Cek otorisasi
        if ($lesson->course->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Jika tidak abort, langsung return view
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
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