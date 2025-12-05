<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('curp', 18)->nullable()->after('email');
            $table->date('fecha_nacimiento')->nullable()->after('curp');
            $table->string('genero', 30)->nullable()->after('fecha_nacimiento');
            $table->string('estado_civil', 50)->nullable()->after('genero');
            $table->string('telefono', 20)->nullable()->after('estado_civil');
            $table->string('profesion')->nullable()->after('telefono');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'curp',
                'fecha_nacimiento',
                'genero',
                'estado_civil',
                'telefono',
                'profesion',
            ]);
        });
    }
};

