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
        Schema::create('community_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->longText('content');
            $table->timestamps();
        });

        Schema::create('community_post_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\CommunityPost::class)->constrained()->cascadeOnDelete();
            $table->foreignId('author_id')->references('id')->on('users')->constrained()->cascadeOnDelete();
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_posts');
    }
};
