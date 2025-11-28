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
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('judge')->nullable()->after('comments');
            $table->string('team')->nullable()->after('judge');
            $table->integer('total_score')->nullable()->after('team');
            $table->date('evaluated_at')->nullable()->after('total_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['judge', 'team', 'total_score', 'evaluated_at']);
        });
    }
};
