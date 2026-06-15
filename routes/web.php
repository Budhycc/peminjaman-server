<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AsetController;
use App\Http\Controllers\Admin\PeminjamanController;
use App\Http\Controllers\Admin\PengembalianController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Web Routes
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // User CRUD
    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    // Asset CRUD
    Route::get('/assets', [AsetController::class, 'index'])->name('admin.assets.index');
    Route::get('/assets/create', [AsetController::class, 'create'])->name('admin.assets.create');
    Route::post('/assets', [AsetController::class, 'store'])->name('admin.assets.store');
    Route::get('/assets/{id}/edit', [AsetController::class, 'edit'])->name('admin.assets.edit');
    Route::put('/assets/{id}', [AsetController::class, 'update'])->name('admin.assets.update');
    Route::delete('/assets/{id}', [AsetController::class, 'destroy'])->name('admin.assets.destroy');

    // Peminjaman CRUD
    Route::get('/loans', [PeminjamanController::class, 'index'])->name('admin.loans.index');
    Route::get('/loans/create', [PeminjamanController::class, 'create'])->name('admin.loans.create');
    Route::post('/loans', [PeminjamanController::class, 'store'])->name('admin.loans.store');
    Route::get('/loans/{id}', [PeminjamanController::class, 'show'])->name('admin.loans.show');
    Route::get('/loans/{id}/edit', [PeminjamanController::class, 'edit'])->name('admin.loans.edit');
    Route::put('/loans/{id}', [PeminjamanController::class, 'update'])->name('admin.loans.update');
    Route::delete('/loans/{id}', [PeminjamanController::class, 'destroy'])->name('admin.loans.destroy');

    // Pengembalian CRUD
    Route::get('/returns', [PengembalianController::class, 'index'])->name('admin.returns.index');
    Route::get('/returns/create', [PengembalianController::class, 'create'])->name('admin.returns.create');
    Route::post('/returns', [PengembalianController::class, 'store'])->name('admin.returns.store');
    Route::get('/returns/{id}', [PengembalianController::class, 'show'])->name('admin.returns.show');
    Route::get('/returns/{id}/edit', [PengembalianController::class, 'edit'])->name('admin.returns.edit');
    Route::put('/returns/{id}', [PengembalianController::class, 'update'])->name('admin.returns.update');
    Route::delete('/returns/{id}', [PengembalianController::class, 'destroy'])->name('admin.returns.destroy');
    // Reports can be added later
    Route::get('/reports', function() {
        return view('admin.layouts.app'); // temporary placeholder
    });
});
