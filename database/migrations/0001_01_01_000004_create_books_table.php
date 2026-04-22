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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('pengarang');
            $table->string('penerbit');
            $table->string('isbn')->unique();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->integer('tahun_terbit');
            $table->integer('stok');
            $table->integer('stok_tersedia');
            $table->text('deskripsi')->nullable();
            $table->enum('kondisi', ['baik', 'rusak ringan', 'rusak berat'])->default('baik');
            $table->string('lokasi')->nullable(); // lokasi rak di perpustakaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};
