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
        // Nota: SQLite no soporta modificar ENUM directamente
        // La columna 'status' ya es tipo STRING, solo actualizamos la documentación
        // Los valores válidos son: 'borrador', 'publicado', 'activo', 'cerrado'
        
        DB::statement("
            -- Nuevo status: 'publicado'
            -- Flujo: borrador → publicado → activo → cerrado
            -- 
            -- borrador: No visible para participantes
            -- publicado: Visible pero inscripciones aún no abiertas
            -- activo: Acepta inscripciones
            -- cerrado: Finalizado
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No hay cambios estructurales que revertir
        // El campo 'status' sigue siendo string
    }
};
