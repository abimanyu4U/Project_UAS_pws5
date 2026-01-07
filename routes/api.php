<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\ProductController;  
use App\Http\Controllers\TransactionController; // Import Controller Anggota 3
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- AKSES PUBLIK ---
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// --- AKSES TERPROTEKSI (JWT) ---
Route::middleware('auth:api')->group(function () {
    
    // 1. Fitur Autentikasi (Tugas Ketua)
    Route::post('logout', [AuthController::class, 'logout']);
    
    // 2. Fitur CRUD Resource (Tugas Anggota 2)
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    // 3. Fitur Transaksi Checkout (Tugas Anggota 3)
    // Menambahkan route untuk memproses pembelian
    Route::post('checkout', [TransactionController::class, 'checkout']);
    
});