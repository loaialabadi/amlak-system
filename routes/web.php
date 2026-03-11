<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\CertificateController;
use App\Http\Controllers\UserController;

// ─── Auth ────────────────────────────────────────────────────────────────────

Route::get('/login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

// ─── Authenticated routes ─────────────────────────────────────────────────────

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Sales (بيوع)
    Route::resource('sales', SaleController::class);

    // Certificates (إفادات)
    Route::prefix('sales/{sale}/certificates')->name('sales.certificates.')->group(function () {
        Route::get('create',  [CertificateController::class, 'create'])->name('create');
        Route::post('/',      [CertificateController::class, 'store'])->name('store');
    });
    Route::get('certificates/{certificate}', [CertificateController::class, 'show'])->name('certificates.show');

    // Users (admin only)
    Route::resource('users', UserController::class)->except(['show']);

});
