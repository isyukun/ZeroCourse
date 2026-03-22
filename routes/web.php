<?php

use App\Http\Controllers\{
    ProfileController, CourseController, ModuleController, 
    LessonController, EnrollmentController, ProgressController, 
    DashboardController, QuizController
};
use Illuminate\Support\Facades\Route;

/**
 * BAGIAN 1: PUBLIC ROUTES
 * Halaman yang bisa diakses tanpa login.
 */
Route::get('/', function () {
    return view('welcome');
});

/**
 * BAGIAN 2: AUTHENTICATED ROUTES (SISWA & UMUM)
 * Semua fitur di bawah ini memerlukan login.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengaturan Profil (Breeze Default)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    /**
     * BAGIAN 3: KATALOG, PENDAFTARAN & SERTIFIKAT
     */
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollment.store');
    Route::get('/courses/{course}/certificate', [CourseController::class, 'downloadCertificate'])->name('courses.certificate');

    /**
     * BAGIAN 4: LEARNING PLAYER (MATERI & KUIS)
     * Route untuk siswa belajar dan mengerjakan kuis.
     */
    // Progress Materi
    Route::post('/lessons/{lesson}/complete', [LessonController::class, 'complete'])->name('lessons.complete');
    Route::get('/lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    
    // Kuis Player (Siswa)
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    /**
     * BAGIAN 5: INSTRUKTUR (RESOURCE MANAGEMENT)
     * Hanya bisa diakses oleh user dengan role 'instructor'.
     */
    Route::middleware(['instructor'])->group(function () {
        
        // Manajemen Kursus (Tanpa Index & Show karena sudah di atas)
        Route::resource('courses', CourseController::class)->except(['index', 'show']);

        // Manajemen Modul & Materi (Shallow URL agar rapi)
        // Contoh URL: /modules/1/lessons/create
        Route::resource('courses.modules', ModuleController::class)->shallow()->only(['store', 'update', 'destroy']);
        Route::resource('modules.lessons', LessonController::class)->shallow()->except(['show']);

        // Manajemen Kuis (Instruktur)
        Route::get('/modules/{module}/quizzes/create', [QuizController::class, 'create'])->name('modules.quizzes.create');
        Route::post('/modules/{module}/quizzes', [QuizController::class, 'store'])->name('modules.quizzes.store');
        Route::resource('quizzes', QuizController::class)->only(['edit', 'update', 'destroy']);
    });

    /**
     * BAGIAN 6: DETAIL KURSUS (SLUG)
     * Diletakkan paling bawah agar tidak bentrok dengan route /courses/... lainnya.
     */
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
});

require __DIR__.'/auth.php';