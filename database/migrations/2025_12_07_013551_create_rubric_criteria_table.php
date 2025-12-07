<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubric_criteria', function (Blueprint $table) {
            $table->id();

            // Relación con rubrics
            $table->foreignId('rubric_id')
                  ->constrained('rubrics')
                  ->cascadeOnDelete();

            $table->string('name');              // Innovación, Usabilidad, etc.
            $table->text('description')->nullable();
            $table->unsignedInteger('weight')->default(0);    // Peso (porcentaje o puntos)
            $table->unsignedInteger('min_score')->default(0); // Puntaje mínimo
            $table->unsignedInteger('max_score')->default(10);// Puntaje máximo

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubric_criteria');
    }
};

