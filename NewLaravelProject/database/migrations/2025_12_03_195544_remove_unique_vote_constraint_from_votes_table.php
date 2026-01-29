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
            // 1️⃣ Primero eliminamos la foreign key que depende del índice (ajusta el nombre si es diferente)
            $table->dropForeign(['user_id']);

            // 2️⃣ Ahora sí eliminamos la restricción única
            $table->dropUnique(['user_id', 'voted_at']);

            // 3️⃣ Opcional: recrear la foreign key después
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            // Restaurar la restricción única
            $table->unique(['user_id', 'voted_at']);

            // Restaurar la foreign key si la eliminaste
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
