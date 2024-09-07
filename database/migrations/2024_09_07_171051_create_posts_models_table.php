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
        Schema::create('posts_models', function (Blueprint $table) {
            $table->id();
            $table->string("userid");
            $table->text("post");
            $table->string("media")->nullable();
            $table->string("addedby");
            $table->string("status")->default("active");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts_models');
    }
};
