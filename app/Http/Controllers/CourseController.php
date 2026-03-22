<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    /**
     * BAGIAN 1: INDEX & LISTING
     * Menampilkan semua kursus yang tersedia untuk siswa.
     */
    public function index()
    {
        // Eager Loading 'category' untuk optimasi query (N+1 Problem)
        $courses = Course::with('category')->latest()->paginate(9);
        return view('courses.index', compact('courses'));
    }

    /**
     * BAGIAN 2: CREATE & STORE
     * Proses pembuatan kursus baru oleh instruktur.
     */
    public function create()
    {
        $categories = Category::all();
        return view('courses.create', compact('categories'));
    }

    public function store(StoreCourseRequest $request)
    {
        $validated = $request->validated();
        
        // Handle Upload Thumbnail dengan path yang rapi
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        // Generate SLUG unik
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);

        // Simpan melalui relasi User agar user_id otomatis terisi
        $request->user()->courses()->create($validated);

        return redirect()->route('courses.index')->with('success', 'Kursus berhasil diterbitkan!');
    }
        
    /**
     * BAGIAN 3: SHOW & PROGRESS TRACKING
     * Menampilkan materi dan menghitung persentase penyelesaian user.
     */
    public function show($slug)
    {
        $course = Course::with(['category', 'modules.lessons', 'modules.quiz'])
            ->where('slug', $slug)
            ->firstOrFail();

        $progressPercentage = 0;
        $completedLessons = [];

        if (Auth::check()) {
            $user = Auth::user();
            
            // Ambil ID lesson yang sudah diselesaikan user di kursus ini
            $completedLessons = $user->progress()
                ->where('course_id', $course->id)
                ->where('status', 'completed')
                ->pluck('lesson_id')
                ->toArray();

            // Hitung total item (Lesson + Quiz)
            $totalLessons = $course->modules->flatMap->lessons->count();
            $totalQuizzes = $course->modules->whereNotNull('quiz')->count();
            $totalItems = $totalLessons + $totalQuizzes;

            if ($totalItems > 0) {
                $progressPercentage = round((count($completedLessons) / $totalItems) * 100);
            }
        }

        return view('courses.show', compact('course', 'completedLessons', 'progressPercentage'));
    }

    /**
     * BAGIAN 4: EDIT & UPDATE
     * Mengelola pembaruan data kursus dan validasi kepemilikan.
     */
    public function edit(Course $course)
    {
        // Security Check: Hanya pemilik kursus yang bisa edit
        if ($course->user_id !== Auth::id()) {
            abort(403, 'Akses ditolak.');
        }

        $categories = Category::all();
        return view('courses.edit', compact('course', 'categories'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        if ($course->user_id !== Auth::id()) { abort(403); }

        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            // Hapus file lama dari storage agar tidak memenuhi server
            if ($course->thumbnail) {
                Storage::disk('public')->delete($course->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('course-thumbnails', 'public');
        }

        // Update slug jika judul berubah (opsional, tergantung kebutuhan SEO)
        if ($course->title !== $validated['title']) {
            $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(5);
        }

        $course->update($validated);

        return redirect()->route('courses.show', $course->slug)->with('success', 'Perubahan berhasil disimpan!');
    }

    /**
     * BAGIAN 5: CERTIFICATE GENERATION
     * Membuat PDF sertifikat menggunakan DomPDF.
     */
    public function downloadCertificate(Course $course)
    {
        $user = Auth::user();
        
        // Validasi: Pastikan progres sudah 100%
        $totalItems = $course->modules->flatMap->lessons->count();
        $userCompleted = $user->progress()->where('course_id', $course->id)->where('status', 'completed')->count();
        
        if ($totalItems === 0 || $userCompleted < $totalItems) {
            return back()->with('error', 'Silakan selesaikan semua materi untuk mengunduh sertifikat.');
        }

        $data = [
            'name'      => strtoupper($user->name),
            'course'    => $course->title,
            'date'      => now()->translatedFormat('d F Y'),
            'instructor'=> $course->user->name,
            'id'        => 'CERT-' . strtoupper(Str::random(8))
        ];

        $pdf = Pdf::loadView('pdf.certificate', $data)->setPaper('a4', 'landscape');
        
        return $pdf->download('Sertifikat_' . Str::snake($course->title) . '.pdf');
    }
}