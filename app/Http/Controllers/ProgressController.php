<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function store(Lesson $lesson)
    {
        // 1. Simpan data ke tabel pivot (sudah benar)
        Auth::user()->completedLessons()->syncWithoutDetaching([
            $lesson->id => ['completed_at' => now()]
        ]);

        // 2. LOGIC NEXT LESSON: Cari materi berikutnya
        // Kita cari materi di modul yang sama yang ID-nya lebih besar dari materi sekarang
        $nextLesson = Lesson::where('module_id', $lesson->module_id)
            ->where('id', '>', $lesson->id)
            ->orderBy('id', 'asc')
            ->first();

        // 3. Jika tidak ada di modul yang sama, cari materi pertama di MODUL BERIKUTNYA
        if (!$nextLesson) {
            $nextModule = $lesson->module->course->modules()
                ->where('id', '>', $lesson->module_id)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextModule) {
                $nextLesson = $nextModule->lessons()->orderBy('id', 'asc')->first();
            }
        }

        // 4. Redirect berdasarkan hasil pencarian
        if ($nextLesson) {
            return redirect()->route('lessons.show', $nextLesson->id)
                ->with('success', 'Materi selesai! Lanjut ke materi berikutnya.');
        }

        // Kalau sudah mentok (materi terakhir di modul terakhir), balik ke kursus
        return redirect()->route('courses.show', $lesson->module->course->slug)
            ->with('success', 'Selamat! Kamu telah menyelesaikan semua materi di kursus ini.');
    }

    public function destroy(Lesson $lesson)
    {
        // Menghapus status selesai (jika user berubah pikiran)
        Auth::user()->completedLessons()->detach($lesson->id);

        return back()->with('success', 'Status materi direset.');
    }
}