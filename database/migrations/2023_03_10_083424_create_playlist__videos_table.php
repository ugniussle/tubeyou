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
        Schema::create('playlist__videos', function (Blueprint $table) {
            $table->unsignedBigInteger("playlist_id");
            $table->foreign("playlist_id")->references("id")->on("playlist")->onDelete("cascade");
            $table->unsignedBigInteger("video_id");
            $table->foreign("video_id")->references("id")->on("video")->onDelete("cascade");
            $table->unsignedInteger("position");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlist__videos');
    }
};
