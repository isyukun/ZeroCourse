<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    protected $fillable = [
        'user_id', 
        'course_id', 
        'module_id', 
        'lesson_id', 
        'status', 
        'score', 
        'completed_at'
    ];

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }
}