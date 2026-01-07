<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\ActivityLog;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function checkout(Request $request)
    {
        // 1. Pastikan User sudah login sebelum transaksi
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu (Token tidak valid/kosong)'
            ], 401);
        }

        try {
            return DB::transaction(function () use ($request) {
                
                // 2. Simpan Transaksi Utama
                $transaction = Transaction::create([
                    'user_id' => Auth::id(), // Mengambil ID dari user yang sedang login
                    'invoice_number' => 'INV-' . time(),
                    'total_price' => $request->total_price,
                    'status' => 'success'
                ]);

                // 3. Loop barang yang dibeli
                foreach ($request->items as $item) {
                    // Cek apakah produk benar-benar ada di database Anggota 2
                    $product = Product::find($item['product_id']);
                    
                    if (!$product) {
                        throw new \Exception("Produk dengan ID " . $item['product_id'] . " tidak ditemukan.");
                    }

                    // Cek apakah stok cukup
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok produk " . $product->name . " tidak cukup.");
                    }

                    // Simpan Detail Transaksi
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $item['price']
                    ]);

                    // Update stok milik Anggota 2
                    $product->decrement('stock', $item['quantity']);
                }

                // 4. LOGGING: Catat aktivitas Anggota 3
                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Checkout Berhasil',
                    'description' => 'User berhasil melakukan transaksi dengan invoice ' . $transaction->invoice_number
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkout berhasil, stok diperbarui, dan log tercatat!',
                    'data' => [
                        'invoice' => $transaction->invoice_number,
                        'total_bayar' => $transaction->total_price,
                        'status_transaksi' => $transaction->status
                    ]
                ], 201);
            });
        } catch (\Exception $e) {
            // Menampilkan pesan error spesifik jika terjadi kegagalan
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi Gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}