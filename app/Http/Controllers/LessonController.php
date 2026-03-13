<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
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
        $module = $lesson->module;

        // Navigasi: Cari materi berikutnya dan sebelumnya
        $nextLesson = Lesson::where('module_id', $module->id)
            ->where('order', '>', $lesson->order)
            ->orderBy('order', 'asc')
            ->first();

        $prevLesson = Lesson::where('module_id', $module->id)
            ->where('order', '<', $lesson->order)
            ->orderBy('order', 'desc')
            ->first();

        return view('lessons.show', compact('lesson', 'nextLesson', 'prevLesson'));
    }

    public function edit(Lesson $lesson)
    {
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