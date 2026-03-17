<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $myCourses = collect();
        $enrolledCourses = collect();

        // 1. Logika untuk INSTRUKTUR atau ADMIN
        if ($user->role === 'instructor' || $user->role === 'admin') {
            $myCourses = Course::where('user_id', $user->id)
                ->withCount(['enrolledUsers', 'modules'])
                ->get();
        }

        // 2. Logika untuk SISWA (Menampilkan kelas yang sedang diambil)
        // Kita tetap tampilkan ini untuk instruktur juga jika mereka enroll di kelas lain
        $enrolledCourses = $user->enrolledCourses()->with(['modules.lessons'])->get();

        foreach ($enrolledCourses as $course) {
            $lessonIds = $course->modules->flatMap->lessons->pluck('id');
            $totalLessons = $lessonIds->count();
            
            $completedCount = $user->completedLessons()
                                   ->whereIn('lesson_id', $lessonIds)
                                   ->count();

            $course->progress = $totalLessons > 0 ? round(($completedCount / $totalLessons) * 100) : 0;
            $course->first_lesson = $course->modules->first()?->lessons->first();
        }

        return view('dashboard', compact('enrolledCourses', 'myCourses', 'user'));
    }
}