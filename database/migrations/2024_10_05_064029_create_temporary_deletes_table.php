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
        Schema::create('temporary_deletes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\CommunityPost::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(\App\Models\CommunityPostAttachment::class)->constrained()->cascadeOnDelete();
            $table->string('original_name');
            $table->string('name');
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_deletes');
    }
};
