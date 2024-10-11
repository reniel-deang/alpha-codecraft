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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Classroom::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('description');
            $table->integer('sections');
            $table->enum('status', ['published', 'unpublished'])->default('unpublished');
            $table->timestamps();
        });

        Schema::create('lesson_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Lesson::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longtext('content');
            $table->timestamps();
        });

        Schema::create('progress', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Lesson::class)->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->integer('completed_sections');
            $table->json('completed_sections_id');
            $table->timestamps();
        });

        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Lesson::class)->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
        Schema::dropIfExists('lesson_sections');
        Schema::dropIfExists('progress');
        Schema::dropIfExists('certificates');
    }
};
