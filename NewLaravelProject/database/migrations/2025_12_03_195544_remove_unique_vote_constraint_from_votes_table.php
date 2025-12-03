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
        Schema::table('votes', function (Blueprint $table) {
            // Eliminar la restricción única para permitir múltiples votos por día a administradores
            $table->dropUnique(['user_id', 'voted_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Restaurar la restricción única si se revierte la migración
            $table->unique(['user_id', 'voted_at']);
        });
    }
};
