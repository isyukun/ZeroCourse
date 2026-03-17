<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route Utama (Bisa diakses semua user yang login)
Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Katalog Kursus & Belajar (Umum)
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
    
    // Enrollment & Progress
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('courses.enroll');
    Route::post('lessons/{lesson}/complete', [ProgressController::class, 'store'])->name('lessons.complete');
    Route::delete('lessons/{lesson}/complete', [ProgressController::class, 'destroy'])->name('lessons.incomplete');

    // Melihat Materi (Show saja yang boleh diakses siswa)
    Route::get('lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
});

// --- PROTEKSI INSTRUKTUR (Hanya Role Instructor/Admin) ---
Route::middleware(['auth', 'verified', 'instructor'])->group(function () {
    
    // CRUD Kursus (Selain Index & Show)
    Route::resource('courses', CourseController::class)->except(['index', 'show']);

    // Manajemen Modul
    Route::post('courses/{course}/modules', [ModuleController::class, 'store'])->name('modules.store');
    Route::put('/modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
    Route::delete('/modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');

    // Manajemen Materi (CRUD selain Show)
    Route::get('/modules/{module}/lessons/create', [LessonController::class, 'create'])->name('lessons.create');
    Route::post('modules/{module}/lessons', [LessonController::class, 'store'])->name('lessons.store');
    Route::get('/lessons/{lesson}/edit', [LessonController::class, 'edit'])->name('lessons.edit');
    Route::put('/lessons/{lesson}', [LessonController::class, 'update'])->name('lessons.update');
    Route::delete('/lessons/{lesson}', [LessonController::class, 'destroy'])->name('lessons.destroy');
});

require __DIR__.'/auth.php';