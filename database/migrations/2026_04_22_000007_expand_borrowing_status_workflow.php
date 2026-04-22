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
        DB::statement("
            ALTER TABLE borrowings
            MODIFY status ENUM(
                'menunggu_persetujuan',
                'aktif',
                'menunggu_pengembalian',
                'dikembalikan',
                'ditolak',
                'hilang'
            ) NOT NULL DEFAULT 'menunggu_persetujuan'
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE borrowings
            MODIFY status ENUM('aktif', 'dikembalikan', 'hilang') NOT NULL DEFAULT 'aktif'
        ");
    }
};
