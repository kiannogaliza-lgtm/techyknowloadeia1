<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE home_service_requests MODIFY COLUMN status ENUM('Pending', 'Approved', 'Confirmed', 'Completed', 'Rejected') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE home_service_requests MODIFY COLUMN status ENUM('Pending', 'Approved', 'Completed', 'Rejected') DEFAULT 'Pending'");
    }
};
