<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use App\Models\User;
use App\Http\Requests\StoreLessonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    /**
     * BAGIAN 1: CREATE & STORE (PENGELOLAAN MATERI)
     * Menambahkan materi baru ke dalam modul tertentu.
     */
    public function create(Module $module)
    {
        // Pastikan hanya pemilik kursus yang bisa menambah materi
        if ($module->course->user_id !== Auth::id()) { abort(403); }
        
        return view('lessons.create', compact('module'));
    }

    public function store(StoreLessonRequest $request, Module $module)
    {
        if ($module->course->user_id !== Auth::id()) { abort(403); }

        $validated = $request->validated();
        
        // Auto-increment urutan materi dalam modul tersebut
        $validated['order'] = $module->lessons()->max('order') + 1;

        $lesson = $module->lessons()->create($validated);

        return redirect()->route('courses.show', $module->course->slug)
                         ->with('success', "Materi '{$lesson->title}' berhasil ditambahkan!");
    }

    /**
     * BAGIAN 2: SHOW & LOCK SYSTEM (PENGALAMAN BELAJAR)
     * Menampilkan konten materi dengan sistem pengunci materi sebelumnya.
     */
    public function show(Lesson $lesson)
    {
        $user = Auth::user();
        $course = $lesson->module->course;
        
        // 1. Eager Load data untuk Sidebar & Navigasi
        $lesson->load('module.course');
        $allModules = $course->modules()->with('lessons')->orderBy('order', 'asc')->get();

        // 2. Otorisasi Akses
        $isOwner = $course->user_id === $user->id;
        $isEnrolled = $user->enrolledCourses->contains($course->id);

        if (!$isOwner && !$isEnrolled) {
            abort(403, 'Silakan daftar kursus terlebih dahulu.');
        }

        // 3. Logika "Locked Content" (Hanya untuk Siswa)
        if (!$isOwner) {
            $prevLesson = Lesson::where('module_id', $lesson->module_id)
                ->where('order', '<', $lesson->order)
                ->orderBy('order', 'desc')
                ->first();

            // Jika ada materi sebelumnya dan belum selesai, tendang balik
            if ($prevLesson && !Auth::user()->progress()->where('lesson_id', $prevLesson->id)->where('status', 'completed')->exists()) {
                return redirect()->route('lessons.show', $prevLesson->id)
                                 ->with('error', 'Selesaikan materi sebelumnya dulu ya!');
            }
        }

        // 4. Navigasi Next & Previous (Lintas Modul)
        $nextLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        return view('lessons.show', compact('lesson', 'allModules', 'nextLesson', 'prevLesson', 'isOwner'));
    }

    /**
     * BAGIAN 3: EDIT, UPDATE & DESTROY
     * Manajemen pembaruan dan penghapusan materi.
     */
    public function edit(Lesson $lesson)
    {
        if ($lesson->module->course->user_id !== Auth::id()) { abort(403); }
        return view('lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        if ($lesson->module->course->user_id !== Auth::id()) { abort(403); }

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
        // PENTING: Perbaikan pengecekan user_id lewat relasi yang benar
        if ($lesson->module->course->user_id !== Auth::id()) { abort(403); }

        $lesson->delete();
        return redirect()->back()->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * BAGIAN 4: COMPLETION SYSTEM
     * Menandai materi sebagai selesai untuk membuka materi berikutnya.
     */
    public function complete(Lesson $lesson)
    {
        // Menggunakan updateOrCreate pada tabel progress (sesuai struktur Dashboard)
        Auth::user()->progress()->updateOrCreate(
            ['lesson_id' => $lesson->id, 'course_id' => $lesson->module->course_id],
            ['status' => 'completed', 'completed_at' => now()]
        );

        return back()->with('success', 'Materi selesai! Lanjutkan ke materi berikutnya.');
    }
}