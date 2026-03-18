<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        CategorySeeder::class, // 1. Kategori dulu
        CourseSeeder::class,   // 2. Kursus
        ModuleSeeder::class,   // 3. Modul (Pastikan sudah buat ini juga)
        LessonSeeder::class,   // 4. Baru Lesson
        QuizSeeder::class,     // 5. Quiz
        ]);
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}
