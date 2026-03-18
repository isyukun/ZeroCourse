<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    // Menampilkan daftar kursus
    public function index()
    {
        $courses = Course::with('category')->latest()->paginate(10);
        return view('courses.index', compact('courses'));
    }

    // Form buat kursus baru
    public function create()
    {
        $categories = Category::all();
        return view('courses.create', compact('categories'));
    }

    // Simpan kursus ke database
    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        
        // 1. Handle Upload Thumbnail
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        // 2. Generate SLUG otomatis menggunakan helper Str
        $validated['slug'] = Str::slug($validated['title']);

        // 3. Simpan data (Memastikan relasi ke user yang login terjaga)
        $request->user()->courses()->create($validated);

        return redirect()->route('dashboard')->with('success', 'Kursus berhasil dibuat!');
    }
        
    // Menampilkan detail kursus berdasarkan slug
    public function show($slug)
    {
        // 1. Ambil data kursus beserta relasinya
        $course = Course::with(['category', 'modules.lessons', 'modules.quiz'])
            ->where('slug', $slug)
            ->firstOrFail();

        // 2. Ambil data progress user yang sedang login untuk kursus ini
        $userProgress = [];
        if (Auth::check()) {
            $userProgress = Auth::user()->progress()
                ->where('course_id', $course->id)
                ->get();
        }

        // 3. Mapping ID materi & modul yang sudah selesai agar mudah dicek di Blade
        $completedLessons = $userProgress->pluck('lesson_id')->toArray();
        $completedModules = $userProgress->pluck('module_id')->toArray();

        return view('courses.show', compact('course', 'completedLessons', 'completedModules'));
    }

    // Menampilkan halaman form edit
    public function edit(Course $course)
    {
        // Gunakan Auth::user()->id untuk menghilangkan warning di VS Code
        if ($course->user_id !== Auth::user()->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah kursus ini.');
        }

        $categories = Category::all(); // Tambahkan ini jika di form edit butuh pilih kategori
        return view('courses.edit', compact('course', 'categories'));
    }

    // Memproses pembaruan data ke database
    public function update(UpdateCourseRequest $request, Course $course)
    {
        // Otorisasi ulang
        if ($course->user_id !== Auth::user()->id) {
            abort(403);
        }

        $validated = $request->validated();

        // Logika Handle Thumbnail Baru
        if ($request->hasFile('thumbnail')) {
            // Hapus foto lama jika ada
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            
            // Simpan foto baru
            $validated['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        // Update slug otomatis jika judul berubah
        $validated['slug'] = Str::slug($validated['title']);

        $course->update($validated);

        return redirect()->route('dashboard')->with('success', 'Kursus berhasil diperbarui!');
    }
}