<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            // nullable karena progress bisa spesifik ke modul (untuk quiz) atau lesson
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('cascade');
            
            $table->string('status')->default('completed'); // 'started', 'completed'
            $table->integer('score')->nullable(); // Khusus untuk hasil Quiz
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Mencegah duplikasi: satu user hanya punya satu record per lesson/quiz
            $table->unique(['user_id', 'lesson_id'], 'user_lesson_unique');
            $table->unique(['user_id', 'module_id'], 'user_module_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress');
    }
};
