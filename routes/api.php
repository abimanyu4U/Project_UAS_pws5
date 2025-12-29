<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Akses Publik (Siapa saja bisa mendaftar dan masuk)
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Akses Terproteksi (Wajib menyertakan Token JWT di Header)
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Tempat kodingan Anggota 2 & 3 nanti (CRUD Produk, Kategori, dll)
});