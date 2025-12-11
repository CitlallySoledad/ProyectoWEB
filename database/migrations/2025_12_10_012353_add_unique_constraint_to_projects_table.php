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
        Schema::table('projects', function (Blueprint $table) {
            // Agregar índice único compuesto para team_id y event_id
            // Esto permite que un equipo tenga múltiples proyectos, uno por evento
            $table->unique(['team_id', 'event_id'], 'projects_team_event_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            // Eliminar el índice único
            $table->dropUnique('projects_team_event_unique');
        });
    }
};
