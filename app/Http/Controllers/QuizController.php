<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Option;
use App\Models\Module;
use App\Models\Progress;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    /**
     * BAGIAN 1: PENGELOLAAN KUIS (INSTRUKSIONAL)
     * Membuat kuis baru untuk modul tertentu.
     */
    public function create(Module $module)
    {
        // Pastikan hanya pemilik kursus yang bisa membuat kuis
        if ($module->course->user_id !== Auth::id()) { abort(403); }
        
        return view('quizzes.create', compact('module'));
    }

    public function store(Request $request, Module $module)
    {
        if ($module->course->user_id !== Auth::id()) { abort(403); }

        $request->validate([
            'title' => 'required|string|max:255',
            'minimum_score' => 'required|integer|min:0|max:100',
            'questions.*.text' => 'required|string',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required',
        ]);

        // Gunakan Transaction agar jika satu soal gagal, kuis tidak tersimpan setengah-setengah
        DB::transaction(function () use ($request, $module) {
            $quiz = $module->quiz()->updateOrCreate(
                ['module_id' => $module->id],
                [
                    'title' => $request->title,
                    'minimum_score' => $request->minimum_score,
                ]
            );

            // Bersihkan soal lama jika ini adalah update kuis
            $quiz->questions()->delete();

            foreach ($request->questions as $qIndex => $qData) {
                $question = $quiz->questions()->create([
                    'question_text' => $qData['text']
                ]);

                foreach ($qData['options'] as $oIndex => $oData) {
                    $question->options()->create([
                        'option_text' => $oData['text'],
                        'is_correct' => $qIndex . '-' . $oIndex == $qData['correct']
                    ]);
                }
            }
        });

        return redirect()->route('courses.show', $module->course->slug)
                         ->with('success', 'Kuis berhasil dikonfigurasi!');
    }

    /**
     * BAGIAN 2: LOGIKA PENGERJAAN KUIS (SISWA)
     * Menghitung skor dan menentukan status kelulusan.
     */
    public function submit(Request $request, Quiz $quiz)
    {
        $user = Auth::user();
        $answers = $request->input('answers', []); 
        $totalQuestions = $quiz->questions()->count();
        $correctAnswersCount = 0;

        if ($totalQuestions === 0) {
            return back()->with('error', "Kuis ini belum siap digunakan.");
        }

        // Hitung jawaban benar secara efisien
        foreach ($answers as $questionId => $optionId) {
            $isCorrect = Option::where('id', $optionId)
                ->where('question_id', $questionId)
                ->where('is_correct', true)
                ->exists();
                
            if ($isCorrect) { $correctAnswersCount++; }
        }

        // Kalkulasi Skor Akhir
        $score = round(($correctAnswersCount / $totalQuestions) * 100);

        /**
         * BAGIAN 3: UPDATE PROGRESS & KELULUSAN
         * Jika lulus, modul akan terbuka (unlocked) bagi siswa.
         */
        if ($score >= $quiz->minimum_score) {
            Progress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $quiz->module->course_id,
                    'module_id' => $quiz->module_id,
                ],
                [
                    'status' => 'completed',
                    'score' => $score,
                    'completed_at' => now()
                ]
            );

            return redirect()->route('courses.show', $quiz->module->course->slug)
                             ->with('success', "Luar biasa! Skor Anda $score%. Modul ini telah selesai.");
        }

        return back()->with('error', "Skor Anda $score%. Belum mencapai batas minimum ($quiz->minimum_score%). Silakan pelajari kembali materinya.");
    }
}