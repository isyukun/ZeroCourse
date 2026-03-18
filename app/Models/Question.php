<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = ['quiz_id', 'question_text'];

    // Pertanyaan ini milik satu Quiz
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    // Pertanyaan ini punya banyak pilihan jawaban
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
