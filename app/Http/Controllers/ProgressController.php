<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller
{
    public function store(Lesson $lesson)
    {
        // Menyimpan data ke tabel pivot lesson_user
        Auth::user()->completedLessons()->syncWithoutDetaching([
            $lesson->id => ['completed_at' => now()]
        ]);

        return back()->with('success', 'Materi ditandai sebagai selesai!');
    }

    public function destroy(Lesson $lesson)
    {
        // Menghapus status selesai (jika user berubah pikiran)
        Auth::user()->completedLessons()->detach($lesson->id);

        return back()->with('success', 'Status materi direset.');
    }
}