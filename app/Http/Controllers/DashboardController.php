<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil kursus yang diikuti siswa, sekalian load module dan lesson-nya
        $enrolledCourses = $user->enrolledCourses()->with(['modules.lessons'])->get();

        // Hitung progres untuk setiap kursus
        foreach ($enrolledCourses as $course) {
            // Ambil semua ID lesson dalam satu kursus
            $lessonIds = $course->modules->flatMap->lessons->pluck('id');
            
            // Hitung jumlah total materi
            $totalLessons = $lessonIds->count();
            
            // Hitung berapa yang sudah diselesaikan user
            $completedLessons = $user->completedLessons()
                                     ->whereIn('lesson_id', $lessonIds)
                                     ->count();

            // Hitung persentase
            $course->progress = $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
        }

        return view('dashboard', compact('enrolledCourses'));
    }
}