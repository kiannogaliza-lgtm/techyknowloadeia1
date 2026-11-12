<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * - Adds id_photo column
     * - Extends status enum to include 'pending'
     * - Sets default for new residents to 'pending'
     */
    public function up(): void
    {
        // Add the id_photo column
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_photo')->nullable()->after('profile_photo');
        });

        // Alter status enum to add 'pending' (MySQL approach)
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('pending','active','inactive') NOT NULL DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_photo');
        });

        // Revert enum — set pending users back to active first to avoid data loss
        DB::statement("UPDATE users SET status = 'active' WHERE status = 'pending'");
        DB::statement("ALTER TABLE users MODIFY COLUMN status ENUM('active','inactive') NOT NULL DEFAULT 'active'");
    }
};
