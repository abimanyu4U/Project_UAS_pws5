<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Data yang boleh diisi
    protected $fillable = ['user_id', 'invoice_number', 'total_price', 'status'];

    // Relasi: Satu transaksi memiliki banyak detail produk
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}