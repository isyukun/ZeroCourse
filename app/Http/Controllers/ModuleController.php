<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Http\Requests\StoreModuleRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    /**
     * BAGIAN 1: PENYIMPANAN MODUL
     * Menambahkan modul baru ke dalam sebuah kursus.
     */
    public function store(StoreModuleRequest $request, Course $course)
    {
        // 1. Otorisasi: Pastikan hanya pemilik kursus yang bisa menambah modul
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses untuk memodifikasi kursus ini.');
        }

        $validated = $request->validated();
        
        // 2. Logika Urutan (Robust Order): 
        // Menggunakan max() lebih aman daripada count() jika ada data di tengah yang terhapus
        $validated['order'] = $course->modules()->max('order') + 1;

        // 3. Eksekusi Simpan melalui relasi
        $course->modules()->create($validated);

        return back()->with('success', 'Modul baru berhasil ditambahkan!');
    }

    /**
     * BAGIAN 2: PEMBARUAN MODUL
     * Mengubah judul atau informasi modul.
     */
    public function update(Request $request, Module $module)
    {
        // Akses pengecekan kepemilikan via relasi course
        if ($module->course->user_id !== Auth::id()) { abort(403); }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $module->update($validated);

        return back()->with('success', 'Nama modul berhasil diperbarui!');
    }

    /**
     * BAGIAN 3: PENGHAPUSAN MODUL
     * Menghapus modul beserta relasi di bawahnya (cascade).
     */
    public function destroy(Module $module)
    {
        if ($module->course->user_id !== Auth::id()) { abort(403); }

        $module->delete();

        return back()->with('success', 'Modul telah dihapus dari kursus.');
    }
}