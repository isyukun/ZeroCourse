<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        // Cek apakah sudah terdaftar
        if (Auth::user()->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Kamu sudah terdaftar di kursus ini.');
        }

        // Attach siswa ke kursus
        Auth::user()->enrolledCourses()->attach($course->id);

        return redirect()->route('courses.show', $course->slug)
                         ->with('success', 'Selamat! Kamu berhasil mendaftar.');
    }
}