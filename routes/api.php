<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController; // Import Controller Category
use App\Http\Controllers\ProductController;  // Import Controller Product
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- AKSES PUBLIK ---
// Siapa saja bisa mendaftar dan masuk untuk mendapatkan Token
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


// --- AKSES TERPROTEKSI (JWT) ---
// Semua route di dalam grup ini wajib menyertakan 'Bearer Token' di Header Postman
Route::middleware('auth:api')->group(function () {
    
    // Fitur Autentikasi (Ketua)
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Fitur CRUD Resource (Tugas Anggota 2)
    // apiResource otomatis membuat route: index, store, show, update, destroy
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    
});