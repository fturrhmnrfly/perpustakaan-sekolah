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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->dateTime('tanggal_peminjaman');
            $table->date('tanggal_kembali_rencana');
            $table->dateTime('tanggal_kembali_aktual')->nullable();
            $table->enum('status', ['aktif', 'dikembalikan', 'hilang'])->default('aktif');
            $table->enum('kondisi_kembali', ['baik', 'rusak ringan', 'rusak berat'])->nullable();
            $table->text('keterangan')->nullable();
            $table->decimal('denda', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
