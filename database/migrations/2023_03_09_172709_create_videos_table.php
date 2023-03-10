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
            $table->string("filename", 255);
            $table->text("description");
            $table->string("thumbnail", 255);
            $table->unsignedInteger("views")->default(0);
            $table->unsignedInteger("likes")->default(0);
            $table->unsignedInteger("dislikes")->default(0);
            $table->date("upload_date")->default(now());
            $table->tinyInteger("visibility");
            $table->string("url")->unique();
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
