<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada minimal 1 User (Instructor)
        $instructor = User::first() ?? User::create([
            'name' => 'Muhammad Instructor',
            'email' => 'instructor@zerocourse.test',
            'password' => bcrypt('password'),
            'role' => 'instructor', // Sesuaikan dengan sistem role kamu
        ]);

        // 2. Pastikan ada minimal 1 Kategori
        $category = Category::first() ?? Category::create([
            'name' => 'Web Development',
            'slug' => 'web-development',
        ]);

        // 3. Data Kursus Contoh
        $courses = [
            [
                'user_id' => $instructor->id,
                'category_id' => $category->id,
                'title' => 'Mastering Laravel 11 for Beginners',
                'description' => 'Pelajari Laravel 11 dari nol sampai mahir dengan studi kasus nyata.',
                'image' => 'course-thumbnails/sample-laravel.png', // Path dummy
                'status' => 'published',
            ],
            [
                'user_id' => $instructor->id,
                'category_id' => $category->id,
                'title' => 'Tailwind CSS Mastery',
                'description' => 'Membangun antarmuka modern dan responsif dengan cepat menggunakan Tailwind.',
                'image' => 'course-thumbnails/sample-tailwind.png',
                'status' => 'published',
            ],
        ];

        foreach ($courses as $course) {
            // Slug otomatis dibuat oleh fungsi boot() di model Course kamu
            Course::create($course);
        }
    }
}