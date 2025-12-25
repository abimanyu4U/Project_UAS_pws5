<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi untuk membuat tabel produk.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            
            // 1. Relasi ke tabel categories
            // Pastikan tabel 'categories' sudah dibuat sebelum menjalankan ini
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            
            // 2. Kolom detail produk
            $table->string('name');           // Nama Produk
            $table->text('description');      // Deskripsi Produk
            $table->integer('price');         // Harga (Angka)
            $table->integer('stock');         // Jumlah Stok (Angka)
            
            // 3. Kolom created_at dan updated_at otomatis
            $table->timestamps();
        });
    }

    /**
     * Batalkan migrasi (menghapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};