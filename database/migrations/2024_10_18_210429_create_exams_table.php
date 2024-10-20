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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Lesson::class)->constrained()->cascadeOnDelete();
            $table->integer('time_limit');
            $table->timestamps();
        });

        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Exam::class)->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->timestamps();
        });

        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Exam::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\ExamQuestion::class)->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->text('answer');
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });

        Schema::create('exam_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Exam::class)->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->integer('score');
            $table->boolean('is_pass')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('exam_scores');
    }
};
