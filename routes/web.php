<?php

use App\Http\Controllers\{
    ProfileController, CourseController, ModuleController, 
    LessonController, EnrollmentController, ProgressController, 
    DashboardController, QuizController
};
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (Breeze default menggunakan route manual, kita sesuaikan agar tidak error)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // 1. KATALOG & PENDAFTARAN
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    
    // PERBAIKAN: Pastikan parameter menggunakan {course} agar sinkron dengan route('enrollment.store', $course->id)
    Route::post('/courses/{course}/enroll', [EnrollmentController::class, 'store'])->name('enrollment.store');

    // 2. MATERI, PROGRESS, & QUIZ (SISWA)
    Route::get('lessons/{lesson}', [LessonController::class, 'show'])->name('lessons.show');
    Route::post('lessons/{lesson}/complete', [ProgressController::class, 'store'])->name('lessons.complete');
    Route::delete('lessons/{lesson}/complete', [ProgressController::class, 'destroy'])->name('lessons.incomplete');
    
    // Quiz Player
    Route::get('/quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    Route::post('/quizzes/{quiz}/submit', [QuizController::class, 'submit'])->name('quizzes.submit');

    // 3. FITUR INSTRUKTUR (RESOURCE MANAGEMENT)
    Route::middleware(['instructor'])->group(function () {
        
        // Resource Kursus
        Route::resource('courses', CourseController::class)->except(['index', 'show']);

        // Resource Modul & Materi (Shallow agar URL ringkas)
        Route::resource('courses.modules', ModuleController::class)->shallow()->only(['store', 'update', 'destroy']);
        Route::resource('modules.lessons', LessonController::class)->shallow()->except(['show']);

        // Resource Quiz (Instruktur)
        // Gunakan parameter 'module' secara eksplisit agar mudah ditangkap Controller
        Route::get('/modules/{module}/quizzes/create', [QuizController::class, 'create'])->name('modules.quizzes.create');
        Route::post('/modules/{module}/quizzes', [QuizController::class, 'store'])->name('modules.quizzes.store');
        Route::resource('quizzes', QuizController::class)->only(['edit', 'update', 'destroy']);
    });

    // 4. DETAIL KURSUS (SLUG) - HARUS PALING BAWAH
    Route::get('/courses/{course:slug}', [CourseController::class, 'show'])->name('courses.show');
});

require __DIR__.'/auth.php';