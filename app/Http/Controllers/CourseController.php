<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
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
        
        // Handle upload gambar jika ada
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('course-thumbnails', 'public');
        }

        // Simpan data dengan user_id dari user yang login
        $request->user()->courses()->create($validated);

        return redirect()->route('courses.index')->with('success', 'Kursus berhasil dibuat!');
    }
        
    // Menampilkan detail kursus berdasarkan slug
    public function show($slug)
    {
        // Cari kursus berdasarkan slug, jika tidak ada tampilkan 404
        $course = Course::with(['category', 'modules.lessons'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('courses.show', compact('course'));
    }
}