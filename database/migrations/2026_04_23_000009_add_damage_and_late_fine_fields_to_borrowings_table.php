<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('borrowings', function (Blueprint $table) {
            $table->decimal('denda_keterlambatan', 10, 2)->default(0)->after('denda');
            $table->decimal('denda_kerusakan', 10, 2)->default(0)->after('denda_keterlambatan');
        });

        DB::statement("
            ALTER TABLE borrowings
            MODIFY kondisi_kembali ENUM('baik', 'rusak ringan', 'rusak berat', 'hilang') NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("
            ALTER TABLE borrowings
            MODIFY kondisi_kembali ENUM('baik', 'rusak ringan', 'rusak berat') NULL
        ");

        Schema::table('borrowings', function (Blueprint $table) {
            $table->dropColumn(['denda_keterlambatan', 'denda_kerusakan']);
        });
    }
};
