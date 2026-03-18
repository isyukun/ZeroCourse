<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil modul terakhir (misal: Database & Migrations)
        $module = Module::where('title', 'Database & Migrations')->first() ?? Module::first();

        if ($module) {
            // 1. Buat Quiz-nya
            $quiz = Quiz::create([
                'module_id' => $module->id,
                'title' => 'Evaluasi Pemahaman Database Laravel',
                'minimum_score' => 70, // Syarat lulus 70%
            ]);

            // 2. Pertanyaan Pertama
            $q1 = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'Perintah artisan apa yang digunakan untuk membuat file migrasi baru?',
            ]);

            Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan make:migration', 'is_correct' => true]);
            Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan migrate:generate', 'is_correct' => false]);
            Option::create(['question_id' => $q1->id, 'option_text' => 'php artisan new:migration', 'is_correct' => false]);

            // 3. Pertanyaan Kedua
            $q2 = Question::create([
                'quiz_id' => $quiz->id,
                'question_text' => 'Method apa yang digunakan di file migrasi untuk menghapus tabel jika sudah ada?',
            ]);

            Option::create(['question_id' => $q2->id, 'option_text' => 'Schema::dropIfExists()', 'is_correct' => true]);
            Option::create(['question_id' => $q2->id, 'option_text' => 'Schema::deleteTable()', 'is_correct' => false]);
            Option::create(['question_id' => $q2->id, 'option_text' => 'Schema::remove()', 'is_correct' => false]);
        }
    }
}