<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_scores', function (Blueprint $table) {
            $table->id();

            // Relación a evaluations
            $table->foreignId('evaluation_id')
                  ->constrained('evaluations')
                  ->cascadeOnDelete();

            // Relación a criterio
            $table->foreignId('rubric_criterion_id')
                  ->constrained('rubric_criteria')
                  ->cascadeOnDelete();

            $table->unsignedInteger('score')->nullable();   // puntaje del criterio
            $table->text('comment')->nullable();            // comentario del juez

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_scores');
    }
};
