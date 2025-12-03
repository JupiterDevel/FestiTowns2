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
        Schema::table('advertisements', function (Blueprint $table) {
            $table->boolean('is_adsense')->default(false)->after('active');
            $table->string('adsense_client_id')->nullable()->after('is_adsense');
            $table->string('adsense_slot_id')->nullable()->after('adsense_client_id');
            $table->string('adsense_type')->nullable()->after('adsense_slot_id'); // e.g., 'display', 'in-article', 'in-feed'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('advertisements', function (Blueprint $table) {
            $table->dropColumn(['is_adsense', 'adsense_client_id', 'adsense_slot_id', 'adsense_type']);
        });
    }
};
