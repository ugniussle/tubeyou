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
        Schema::create('video_assets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreign("video_id")->references("id")->on("video")->onDelete("cascade");
            $table->string("thumbnail_full");
            $table->string("thumbnail_small");
            $table->string("thumbnail_original");
            $table->string("video_1080p");
            $table->string("video_720p");
            $table->string("video_480p");
            $table->string("video_360p");
            $table->string("video_240p");
            $table->string("video_original");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_assets');
    }
};
