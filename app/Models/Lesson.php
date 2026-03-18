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

    public function course()
    {
        return $this->module->course(); 
    }
    
    public function completedBy()
    {
        return $this->belongsToMany(User::class, 'lesson_user')
                    ->withPivot('completed_at')
                    ->withTimestamps();
    }

    public function getYoutubeIdAttribute()
    {
        if (!$this->video_url) return null;

        // Regex untuk ambil ID dari berbagai format link YouTube
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->video_url, $match);
        
        return $match[1] ?? null;
    }
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($lesson) {
            $lesson->slug = \Illuminate\Support\Str::slug($lesson->title);
        });
    }
}
