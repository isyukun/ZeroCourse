<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Lesson;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil modul pertama (misal: Pengenalan Laravel)
        $module = Module::first();

        if ($module) {
            $lessons = [
                [
                    'module_id' => $module->id,
                    'title' => 'Instalasi Composer & Laravel',
                    'slug' => 'instalasi-composer-laravel',
                    'content' => 'Langkah-langkah instalasi Laravel 11...',
                    'order' => 1,
                ],
                [
                    'module_id' => $module->id,
                    'title' => 'Struktur Folder Laravel',
                    'slug' => 'struktur-folder-laravel',
                    'content' => 'Penjelasan mengenai folder app, routes, dll...',
                    'order' => 2,
                ],
            ];

            foreach ($lessons as $lesson) {
                Lesson::create($lesson);
            }
        }
    }
}