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
        Schema::table('medicines', function (Blueprint $table) {
            $table->unsignedInteger('dosage_times_per_day')->nullable()->after('dosage');
            $table->unsignedInteger('dosage_days')->nullable()->default(1)->after('dosage_times_per_day');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['dosage_times_per_day', 'dosage_days']);
        });
    }
};
