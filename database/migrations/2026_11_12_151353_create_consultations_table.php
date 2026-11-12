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
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Resident account
            $table->string('patient_name');
            $table->unsignedInteger('patient_age')->nullable();
            $table->string('patient_gender')->nullable();
            $table->string('address')->nullable();
            $table->string('disease_case')->nullable(); // e.g. Dengue, HIV, Tuberculosis, Measles, Hypertension, COVID-19, General
            $table->text('symptoms')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('treatment_notes')->nullable();
            $table->date('consultation_date')->nullable();
            $table->string('attending_nurse')->nullable();
            $table->string('status')->default('Active'); // Active, Recovered, Referred
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};
