<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Http\Requests\StoreModuleRequest;

class ModuleController extends Controller
{
    // Simpan modul baru ke dalam kursus tertentu
    public function store(StoreModuleRequest $request, Course $course)
    {
        $validated = $request->validated();
        
        // Menentukan urutan modul secara otomatis
        $validated['order'] = $course->modules()->count() + 1;

        $course->modules()->create($validated);

        return back()->with('success', 'Modul berhasil ditambahkan!');
    }
}