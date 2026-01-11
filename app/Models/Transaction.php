<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model 
{
    protected $fillable = ['user_id', 'invoice_number', 'total_price', 'status'];

    // Menghubungkan transaksi ke banyak item detailnya
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}