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
        // 1. Add age limit / range to medicines table
        Schema::table('medicines', function (Blueprint $table) {
            $table->unsignedInteger('min_age')->nullable()->after('dosage_days'); // e.g. 12
            $table->unsignedInteger('max_age')->nullable()->after('min_age');     // e.g. 60
        });

        // 2. Add actual patient / recipient information to medicine_requests table
        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->string('patient_type')->default('self')->after('medicine_id'); // 'self' or 'other' / 'family_member'
            $table->string('patient_name')->nullable()->after('patient_type');
            $table->unsignedInteger('patient_age')->nullable()->after('patient_name');
            $table->string('patient_gender')->nullable()->after('patient_age');
            $table->string('patient_relationship')->nullable()->after('patient_gender'); // e.g. Child, Parent, Spouse, Sibling
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropColumn(['min_age', 'max_age']);
        });

        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->dropColumn([
                'patient_type',
                'patient_name',
                'patient_age',
                'patient_gender',
                'patient_relationship',
            ]);
        });
    }
};
