<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = ['module_id', 'title', 'content', 'video_url', 'order'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    
    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot('completed_at')
                    ->withTimestamps();
    }
}
