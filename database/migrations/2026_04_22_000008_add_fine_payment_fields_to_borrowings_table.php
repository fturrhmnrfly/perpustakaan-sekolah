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
        Schema::table('borrowings', function (Blueprint $table) {
            $table->enum('fine_payment_status', ['unpaid', 'paid'])->default('unpaid')->after('denda');
            $table->string('fine_payment_method')->nullable()->after('fine_payment_status');
            $table->string('fine_payment_note')->nullable()->after('fine_payment_method');
            $table->dateTime('fine_paid_at')->nullable()->after('fine_payment_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn([
                'fine_payment_status',
                'fine_payment_method',
                'fine_payment_note',
                'fine_paid_at',
            ]);
        });
    }
};
