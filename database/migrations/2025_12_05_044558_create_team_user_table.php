<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('team_user', function (Blueprint $table) {
        $table->id();

        $table->foreignId('team_id')
              ->constrained('teams')
              ->onDelete('cascade');

        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');

        // Evita duplicados (un usuario no puede entrar dos veces)
        $table->unique(['team_id', 'user_id']);

        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('team_user');
}

};
