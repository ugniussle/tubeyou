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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->string("title");
            $table->string("filename");
            $table->text("description")->nullable();
            $table->string("thumbnail");
            $table->unsignedInteger("views")->default(0);
            $table->unsignedInteger("likes")->default(0);
            $table->unsignedInteger("dislikes")->default(0);
            $table->tinyInteger("visibility");
            $table->string("url_token")->unique();
            $table->string('video_asset')->unique();
            $table->string('thumbnail_asset')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
