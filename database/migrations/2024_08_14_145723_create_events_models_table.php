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
        Schema::create('events_models', function (Blueprint $table) {
            $table->id();
            $table->string("eventname");
            $table->string("eventdate");
            $table->string("eventtime");
            $table->string("description");
            $table->string("banner");
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
        Schema::dropIfExists('events_models');
    }
};
