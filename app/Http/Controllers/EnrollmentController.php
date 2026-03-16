<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(Course $course)
    {
        // 1. Cegah instruktur mendaftar di kursus sendiri
        if ($course->user_id === Auth::id()) {
            return back()->with('error', 'Instruktur tidak perlu mendaftar di kursus sendiri.');
        }

        // 2. Gunakan firstOrCreate untuk mencegah duplikasi pendaftaran
        Enrollment::firstOrCreate([
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ]);

        return redirect()->route('courses.show', $course->slug)
                        ->with('success', 'Selamat! Kamu berhasil mendaftar.');
    }
}