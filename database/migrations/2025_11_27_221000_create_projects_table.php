créate_projects_table
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();

            // Nombre y descripción del proyecto
            $table->string('name');               // Nombre del proyecto
            $table->text('description')->nullable();

            // Relación opcional con equipo
            $table->foreignId('team_id')
                ->nullable()
                ->constrained('teams')
                ->nullOnDelete();

            // Relación opcional con evento
            $table->foreignId('event_id')
                ->nullable()
                ->constrained('events')
                ->nullOnDelete();

            // Estado del proyecto
            $table->enum('status', ['pendiente', 'evaluado'])
                ->default('pendiente');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};

