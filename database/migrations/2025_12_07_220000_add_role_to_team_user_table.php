<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo agregar la columna si NO existe
        if (! Schema::hasColumn('team_user', 'role')) {
            Schema::table('team_user', function (Blueprint $table) {
                $table->string('role')
                      ->default('Miembro')
                      ->after('user_id');
            });
        }
    }

    public function down(): void
    {
        // Solo quitarla si SÍ existe
        if (Schema::hasColumn('team_user', 'role')) {
            Schema::table('team_user', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};

