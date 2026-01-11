<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController; 
use App\Http\Controllers\ProductController;  
use App\Http\Controllers\TransactionController; 
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
    
    Route::post('logout', [AuthController::class, 'logout']);

    // 1. AKSES UMUM (Admin & Customer Bisa Lihat Barang)
    // Kita izinkan GET index dan show untuk semua user yang login
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{id}', [ProductController::class, 'show']);

    // 2. KHUSUS ADMIN (Hanya Bisa Tambah, Ubah, Hapus & Cek Log)
    Route::middleware('role:admin')->group(function () {
        // Kita hanya proteksi fungsi POST, PUT, DELETE
        Route::post('categories', [CategoryController::class, 'store']);
        Route::put('categories/{id}', [CategoryController::class, 'update']);
        Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
        
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{id}', [ProductController::class, 'update']);
        Route::delete('products/{id}', [ProductController::class, 'destroy']);
        
        Route::get('activity-logs', [TransactionController::class, 'activityLogs']);
    });

    // 3. KHUSUS CUSTOMER (Hanya Bisa Belanja)
    Route::middleware('role:customer')->group(function () {
        Route::post('checkout', [TransactionController::class, 'checkout']);
        Route::get('transactions', [TransactionController::class, 'index']);
    });
});