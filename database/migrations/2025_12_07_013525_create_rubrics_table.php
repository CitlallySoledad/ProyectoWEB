<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rubrics', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la rúbrica (p.ej. "Concurso de Innovación")
            
            // Si tienes tabla events, relacionamos:
            $table->foreignId('event_id')->nullable()
                  ->constrained()     // ->references('id')->on('events')
                  ->nullOnDelete();
            
            $table->enum('status', ['activa', 'inactiva'])->default('activa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rubrics');
    }
};

