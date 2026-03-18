<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quiz;
use App\Models\Option;
use App\Models\Module; // Pastikan Model Module di-import
use App\Models\Progress; // Pastikan Model Progress di-import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    // --- BARU: Method untuk nampilin form buat quiz ---
    public function create(Module $module)
    {
        return view('quizzes.create', compact('module'));
    }

    // --- BARU: Method untuk simpan quiz + soal + pilihan ---
    public function store(Request $request, Module $module)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'minimum_score' => 'required|integer|min:0|max:100',
            'questions.*.text' => 'required|string',
            'questions.*.options.*.text' => 'required|string',
            'questions.*.correct' => 'required',
        ]);

        DB::transaction(function () use ($request, $module) {
            $quiz = $module->quiz()->create([
                'title' => $request->title,
                'minimum_score' => $request->minimum_score,
            ]);

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
                         ->with('success', 'Quiz berhasil dibuat!');
    }

    public function submit(Request $request, Quiz $quiz)
    {
        // 1. Ambil input jawaban (Default array kosong jika tidak ada yang dijawab)
        $answers = $request->input('answers', []); 
        $totalQuestions = $quiz->questions()->count();
        $correctAnswersCount = 0;

        // Jaga-jaga jika quiz belum ada soalnya
        if ($totalQuestions === 0) {
            return back()->with('error', "Quiz ini belum memiliki pertanyaan.");
        }

        // 2. Hitung Jawaban Benar
        foreach ($answers as $questionId => $optionId) {
            $isCorrect = Option::where('id', $optionId)
                ->where('question_id', $questionId)
                ->where('is_correct', true)
                ->exists();
                
            if ($isCorrect) {
                $correctAnswersCount++;
            }
        }

        // 3. Kalkulasi Skor (Gunakan round agar angka desimal tidak terlalu panjang)
        $score = round(($correctAnswersCount / $totalQuestions) * 100, 2);

        // 4. Cek Kelulusan
        if ($score >= $quiz->minimum_score) {
            // Logika: Tandai modul ini selesai di tabel progress
            Progress::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'course_id' => $quiz->module->course_id,
                    'module_id' => $quiz->module_id,
                ],
                [
                    'status' => 'completed',
                    'score' => $score, // Simpan skor jika tabel progress mendukung
                    'completed_at' => now()
                ]
            );

            return redirect()->route('courses.show', $quiz->module->course->slug)
                             ->with('success', "Selamat! Kamu lulus dengan skor $score%.");
        }

        return back()->with('error', "Skor kamu $score%. Belum mencapai syarat minimum ($quiz->minimum_score%). Coba lagi!");
    }
}