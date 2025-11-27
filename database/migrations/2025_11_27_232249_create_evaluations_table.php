<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->string('project_name');       // Proyecto A, B, etc.
            $table->string('rubric')->nullable(); // Rúbrica 1, etc.
            $table->unsignedTinyInteger('creativity');    // 0-10
            $table->unsignedTinyInteger('functionality'); // 0-10
            $table->unsignedTinyInteger('innovation');    // 0-10
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
