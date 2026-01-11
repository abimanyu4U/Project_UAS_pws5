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
    /**
     * FUNGSI 1: Melihat Riwayat Transaksi (GET /api/transactions)
     * Pastikan fungsi ini berdiri sendiri di dalam class
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
                        ->with(['details.product'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Daftar riwayat transaksi berhasil diambil',
            'data' => $transactions
        ], 200);
    }

    /**
     * FUNGSI 2: Proses Checkout (POST /api/checkout)
     */
    public function checkout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        try {
            return DB::transaction(function () use ($request) {
                $transaction = Transaction::create([
                    'user_id' => Auth::id(),
                    'invoice_number' => 'INV-' . time(),
                    'total_price' => $request->total_price,
                    'status' => 'success'
                ]);

                foreach ($request->items as $item) {
                    $product = Product::find($item['product_id']);
                    if (!$product) {
                        throw new \Exception("Produk ID " . $item['product_id'] . " tidak ditemukan.");
                    }
                    if ($product->stock < $item['quantity']) {
                        throw new \Exception("Stok produk " . $product->name . " tidak cukup.");
                    }

                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price_at_purchase' => $item['price']
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                ActivityLog::create([
                    'user_id' => Auth::id(),
                    'action' => 'Checkout Berhasil',
                    'description' => 'User melakukan transaksi invoice ' . $transaction->invoice_number
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'Checkout berhasil!',
                    'data' => [
                        'invoice' => $transaction->invoice_number,
                        'total_bayar' => $transaction->total_price
                    ]
                ], 201);
            });
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaksi Gagal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FUNGSI 3: Log Aktivitas Admin (GET /api/activity-logs)
     */
    public function activityLogs()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $logs
        ], 200);
    }
}