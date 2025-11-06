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
        Schema::table('localities', function (Blueprint $table) {
            $table->string('province')->nullable()->after('address');
        });

        Schema::table('festivities', function (Blueprint $table) {
            $table->string('province')->nullable()->after('locality_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('localities', function (Blueprint $table) {
            $table->dropColumn('province');
        });

        Schema::table('festivities', function (Blueprint $table) {
            $table->dropColumn('province');
        });
    }
};
