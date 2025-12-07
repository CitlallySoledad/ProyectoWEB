<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
           $table->id();
            $table->string('name');                     // nombre del proyecto
            $table->foreignId('team_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('event_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pendiente','evaluado'])->default('pendiente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            
        });
    }
};
