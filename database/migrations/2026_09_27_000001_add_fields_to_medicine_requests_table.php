<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('item_service');
            $table->string('delivery_method')->default('pickup')->after('quantity'); // pickup or delivery
            $table->text('notes')->nullable()->after('delivery_method');
            $table->unsignedBigInteger('medicine_id')->nullable()->after('user_id');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('medicine_requests', function (Blueprint $table) {
            $table->dropForeign(['medicine_id']);
            $table->dropColumn(['quantity', 'delivery_method', 'notes', 'medicine_id']);
        });
    }
};
