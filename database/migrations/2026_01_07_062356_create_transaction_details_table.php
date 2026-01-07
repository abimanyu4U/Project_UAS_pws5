<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel transactions di atas
            $table->foreignId('transaction_id')->constrained()->onDelete('cascade');
            // Relasi ke tabel products milik Anggota 2
            $table->foreignId('product_id')->constrained(); 
            $table->integer('quantity');
            $table->integer('price_at_purchase');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};