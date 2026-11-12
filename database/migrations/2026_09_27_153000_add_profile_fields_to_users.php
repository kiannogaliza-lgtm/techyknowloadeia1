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
        Schema::table('users', function (Blueprint $table) {
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('blood_type')->nullable();
            $table->string('address')->nullable();
            $table->string('allergies')->nullable();
            $table->string('chronic_conditions')->nullable();
            $table->date('last_checkup')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_relation')->nullable();
            $table->string('emergency_contact_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'dob', 'gender', 'blood_type', 'address', 'allergies', 
                'chronic_conditions', 'last_checkup', 'emergency_contact_name', 
                'emergency_contact_relation', 'emergency_contact_phone'
            ]);
        });
    }
};
