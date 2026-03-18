<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil kursus pertama (misal: Mastering Laravel)
        $course = Course::first();

        if ($course) {
            $modules = [
                [
                    'course_id' => $course->id,
                    'title' => 'Pengenalan & Instalasi',
                    'order' => 1,
                ],
                [
                    'course_id' => $course->id,
                    'title' => 'Fundamental Laravel (MVC)',
                    'order' => 2,
                ],
                [
                    'course_id' => $course->id,
                    'title' => 'Database & Migrations',
                    'order' => 3,
                ],
            ];

            foreach ($modules as $module) {
                Module::create($module);
            }
        }
    }
}