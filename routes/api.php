<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\LogAktivitasController;
use App\Http\Controllers\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);

    // Asset viewing for users to borrow
    Route::get('/assets/available', [AsetController::class, 'available']);
    Route::get('/assets', [AsetController::class, 'index']);
    Route::get('/assets/{id}', [AsetController::class, 'show']);

    // Peminjaman & Pengembalian for users
    Route::get('/loans/my-history', [PeminjamanController::class, 'myHistory']);
    Route::post('/loans', [PeminjamanController::class, 'store']);
    Route::post('/returns', [PengembalianController::class, 'store']);

    // Admin Only Routes
    Route::middleware('admin')->group(function () {
        // User Management
        Route::apiResource('users', UserController::class);

        // Monitoring Status Aset
        Route::get('/assets/status', [AsetController::class, 'status']);
        Route::get('/assets/borrowed', [AsetController::class, 'borrowed']);

        // Asset Management (CUD)
        Route::post('/assets', [AsetController::class, 'store']);
        Route::put('/assets/{id}', [AsetController::class, 'update']);
        Route::delete('/assets/{id}', [AsetController::class, 'destroy']);
        Route::post('/assets/{id}/generate-qr', [AsetController::class, 'generateQr']);
        
        // QR Code
        Route::post('/scan-qr', [AsetController::class, 'scanQr']);

        // Peminjaman Aset (Admin view)
        Route::get('/loans', [PeminjamanController::class, 'index']);
        Route::get('/loans/{id}', [PeminjamanController::class, 'show']);

        // Pengembalian Aset (Admin view)
        Route::get('/returns', [PengembalianController::class, 'index']);

        // Log Aktivitas
        Route::get('/logs', [LogAktivitasController::class, 'index']);

        // Laporan
        Route::get('/reports/inventory', [ReportController::class, 'inventory']);
        Route::get('/reports/loans', [ReportController::class, 'loans']);
        Route::get('/reports/returns', [ReportController::class, 'returns']);
    });
});
