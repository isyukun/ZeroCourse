<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = ['module_id', 'title', 'minimum_score'];

    // Quiz ini milik satu Modul
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    // Quiz ini punya banyak Pertanyaan
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}