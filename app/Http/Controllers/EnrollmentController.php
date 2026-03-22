<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollmentController extends Controller
{
    /**
     * BAGIAN 1: PROSES PENDAFTARAN (ENROLL)
     * Mengelola logika saat siswa menekan tombol "Daftar Sekarang".
     */
    public function store(Course $course)
    {
        $userId = Auth::id();

        // 1. Validasi Kepemilikan (Security Check)
        if ($course->user_id === $userId) {
            return back()->with('error', 'Sebagai instruktur, Anda sudah memiliki akses penuh ke kursus ini.');
        }

        /**
         * BAGIAN 2: DATABASE TRANSACTION & DEDUPLICATION
         * Memastikan pendaftaran hanya terjadi satu kali dan data konsisten.
         */
        try {
            DB::transaction(function () use ($course, $userId) {
                // Gunakan updateOrCreate atau firstOrCreate untuk mencegah double entry
                Enrollment::firstOrCreate([
                    'user_id'   => $userId,
                    'course_id' => $course->id,
                ], [
                    'enrolled_at' => now(), // Opsional: jika kamu punya kolom tgl daftar
                    'status'      => 'active'
                ]);
            });

            /**
             * BAGIAN 3: REDIRECT & FEEDBACK
             * Mengarahkan user langsung ke materi pertama setelah berhasil daftar.
             */
            return redirect()->route('courses.show', $course->slug)
                             ->with('success', 'Selamat! Pendaftaran berhasil. Ayo mulai belajar!');

        } catch (\Exception $e) {
            // Jika terjadi error pada database
            return back()->with('error', 'Waduh, ada kendala teknis. Silakan coba lagi nanti.');
        }
    }
}