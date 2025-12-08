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
        // Actualizar roles NULL a 'Miembro' por defecto
        DB::table('team_user')
            ->whereNull('role')
            ->update(['role' => 'Miembro']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir los cambios (opcional)
        DB::table('team_user')
            ->where('role', 'Miembro')
            ->update(['role' => null]);
    }
};
