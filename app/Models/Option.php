<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    protected $fillable = ['question_id', 'option_text', 'is_correct'];

    // Casting agar is_correct otomatis jadi boolean (true/false) bukan 1/0
    protected $casts = [
        'is_correct' => 'boolean',
    ];

    // Pilihan ini milik satu Pertanyaan
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
