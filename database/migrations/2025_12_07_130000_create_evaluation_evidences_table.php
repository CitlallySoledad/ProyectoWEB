<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_evidences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->onDelete('cascade');
            $table->string('file_name');           // nombre original del archivo
            $table->string('file_path');           // ruta en storage
            $table->string('mime_type');           // tipo MIME (pdf, image, etc)
            $table->integer('file_size');          // tamaño en bytes
            $table->text('description')->nullable(); // descripción opcional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_evidences');
    }
};
