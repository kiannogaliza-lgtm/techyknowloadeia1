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
        DB::statement("ALTER TABLE medicine_requests MODIFY COLUMN status ENUM('Pending', 'Accepted', 'Approved', 'Rejected', 'Completed') DEFAULT 'Pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE medicine_requests SET status = 'Approved' WHERE status = 'Completed'");
        DB::statement("ALTER TABLE medicine_requests MODIFY COLUMN status ENUM('Pending', 'Accepted', 'Approved', 'Rejected') DEFAULT 'Pending'");
    }
};
