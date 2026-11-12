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
        Schema::create('child_vaccinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); // Parent / Guardian resident
            $table->string('child_name');
            $table->date('child_dob')->nullable();
            $table->string('child_gender')->nullable(); // Male, Female
            $table->string('vaccine_name'); // e.g. BCG, Hepatitis B, Pentavalent (DTP-HepB-Hib), Oral Polio (OPV), Measles-Rubella (MR)
            $table->string('dose_number'); // '1st Dose', '2nd Dose', '3rd Dose', 'Booster 1', 'Booster 2'
            $table->date('date_administered')->nullable();
            $table->string('administered_by')->nullable(); // BHW name / Nurse name / Health Center
            $table->string('status')->default('Completed'); // 'Completed', 'Scheduled', 'Missed'
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_vaccinations');
    }
};
