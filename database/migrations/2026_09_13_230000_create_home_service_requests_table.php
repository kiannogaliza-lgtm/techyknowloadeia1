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
        Schema::create('home_service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('patient_name');
            $table->string('service_type'); // Medical Check-up, Blood Pressure Monitoring, Wound Care, etc.
            $table->string('address');
            $table->string('contact_number');
            $table->date('preferred_date');
            $table->string('preferred_time'); // e.g. 10:00 AM - 12:00 PM
            $table->text('reason_symptoms')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Completed', 'Rejected'])->default('Pending');
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->string('assigned_bhw_name')->nullable();
            $table->text('nurse_notes')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_service_requests');
    }
};
