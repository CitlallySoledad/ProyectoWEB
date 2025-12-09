<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Solo ejecutamos si la tabla y la columna existen
        if (Schema::hasTable('team_user') && Schema::hasColumn('team_user', 'role')) {
            DB::table('team_user')
                ->whereNull('role')
                ->update(['role' => 'Miembro']);
        }
        // Si no existe la columna 'role', no hacemos nada
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No es necesario revertir nada aquí
    }
};

