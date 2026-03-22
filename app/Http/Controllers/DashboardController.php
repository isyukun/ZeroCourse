<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * BAGIAN 1: PENGAMBILAN DATA USER & KURSUS
     * Mengambil data user yang login dan menyiapkan container data.
     */
    public function index()
    {
        $user = Auth::user();
        $myCourses = collect();
        $enrolledCourses = collect();

        /**
         * BAGIAN 2: LOGIKA INSTRUKTUR (KURSUS YANG DIBUAT)
         * Jika user adalah instruktur/admin, ambil kursus yang mereka kelola.
         */
        if ($user->role === 'instructor' || $user->role === 'admin') {
            $myCourses = Course::where('user_id', $user->id)
                ->withCount(['enrolledUsers', 'modules'])
                ->latest()
                ->get();
        }

        /**
         * BAGIAN 3: LOGIKA SISWA (PROGRESS BELAJAR)
         * Mengambil kursus yang diikuti dan menghitung progress secara akurat.
         */
        // Eager load lessons untuk menghindari N+1 query problem
        $enrolledCourses = $user->enrolledCourses()
            ->with(['modules.lessons', 'category'])
            ->get();

        foreach ($enrolledCourses as $course) {
            // Ambil semua ID materi dalam satu kursus
            $lessonIds = $course->modules->flatMap->lessons->pluck('id');
            $totalItems = $lessonIds->count();
            
            // Hitung materi yang sudah diselesaikan user di kursus ini
            $completedCount = $user->progress()
                ->where('course_id', $course->id)
                ->where('status', 'completed')
                ->whereIn('lesson_id', $lessonIds)
                ->count();

            // Inject data progress ke dalam objek kursus secara dinamis
            $course->progress = $totalItems > 0 ? round(($completedCount / $totalItems) * 100) : 0;
            
            // Mengambil materi pertama untuk tombol "Lanjutkan Belajar"
            $course->first_lesson = $course->modules->first()?->lessons->first();
        }

        /**
         * BAGIAN 4: RETURN VIEW
         * Mengirimkan data ke dashboard blade.
         */
        return view('dashboard', compact('enrolledCourses', 'myCourses', 'user'));
    }
}