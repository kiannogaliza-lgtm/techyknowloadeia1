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
        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->string('delivered_by')->nullable()->after('status'); // Name/BHW who delivered or dispensed
            $table->string('claimed_by')->nullable()->after('delivered_by'); // Name of person who claimed/received
            $table->string('claimed_relationship')->nullable()->after('claimed_by'); // Self, Parent, Spouse, Child, Authorized Representative
            $table->timestamp('claimed_at')->nullable()->after('claimed_relationship'); // Exact date/time claimed
            $table->string('proof_photo')->nullable()->after('claimed_at'); // Photo of receipt / signature / claiming proof
            $table->string('receipt_number')->nullable()->after('proof_photo'); // Transaction reference / receipt #
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->dropColumn([
                'delivered_by',
                'claimed_by',
                'claimed_relationship',
                'claimed_at',
                'proof_photo',
                'receipt_number',
            ]);
        });
    }
};
