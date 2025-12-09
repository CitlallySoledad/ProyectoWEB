<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo intentamos borrar la columna si realmente existe
        if (Schema::hasColumn('teams', 'members')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->dropColumn('members');
            });
        }
    }

    public function down(): void
    {
        // Si alguna vez quieres revertir la migración,
        // solo vuelve a crear la columna (ajusta el tipo si era otro)
        if (! Schema::hasColumn('teams', 'members')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->unsignedInteger('members')->default(0);
            });
        }
    }
};
