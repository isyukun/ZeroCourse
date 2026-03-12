<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Lesson;
use App\Http\Requests\StoreLessonRequest;

class LessonController extends Controller
{
    public function store(StoreLessonRequest $request, Module $module)
    {
        $validated = $request->validated();
        
        // Menentukan urutan materi di dalam modul
        $validated['order'] = $module->lessons()->count() + 1;

        $module->lessons()->create($validated);

        return back()->with('success', 'Materi berhasil ditambahkan!');
    }

    // Menampilkan isi materi kepada siswa
    public function show(Lesson $lesson)
    {
        return view('lessons.show', compact('lesson'));
    }
}
