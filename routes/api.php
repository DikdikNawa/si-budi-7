<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CultureController;
use App\Http\Controllers\Api\CultureMediaController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Semua route API akan dikelola di sini.
| Prefix bawaan Laravel untuk API adalah /api/
|--------------------------------------------------------------------------
*/

// 🌍 Route publik untuk budaya
Route::get('/cultures', [CultureController::class, 'index']);
Route::post('/cultures', [CultureController::class, 'store']);

// 📸 Route publik untuk media budaya
Route::get('/media', [CultureMediaController::class, 'index']);
Route::get('/media/{id}', [CultureMediaController::class, 'show']);

// 🔒 Route khusus Admin (pakai autentikasi Laravel Sanctum)
Route::middleware('auth:sanctum')->group(function() {
    // Manajemen budaya
    Route::get('/admin/cultures', [CultureController::class, 'all']);
    Route::put('/admin/cultures/{id}/status', [CultureController::class, 'updateStatus']);
    Route::delete('/admin/cultures/{id}', [CultureController::class, 'destroy']);

    // Manajemen media
    Route::post('/admin/media', [CultureMediaController::class, 'store']);
    Route::put('/admin/media/{id}', [CultureMediaController::class, 'update']);
    Route::delete('/admin/media/{id}', [CultureMediaController::class, 'destroy']);
});
