<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('borrowings')
            ->where('status', 'dikembalikan')
            ->where('denda', '>', 0)
            ->where('fine_payment_status', 'unpaid')
            ->update([
                'status' => 'menunggu_pembayaran',
                'updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('borrowings')
            ->where('status', 'menunggu_pembayaran')
            ->where('denda', '>', 0)
            ->where('fine_payment_status', 'unpaid')
            ->update([
                'status' => 'dikembalikan',
                'updated_at' => now(),
            ]);
    }
};
