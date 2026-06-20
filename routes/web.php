<?php

use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TokoController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'login'])->name('login');
    Route::post('login', [AuthController::class, 'authenticate'])->middleware('throttle:5,1');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('barangs', BarangController::class);

    Route::get('barang-masuk', [BarangMasukController::class, 'create'])->name('barang-masuk.create');
    Route::post('barang-masuk', [BarangMasukController::class, 'store'])->name('barang-masuk.store');
    Route::get('barang-masuk/packing', [BarangMasukController::class, 'createPacking'])->name('barang-masuk.packing');
    Route::post('barang-masuk/packing', [BarangMasukController::class, 'storePacking'])->name('barang-masuk.packing.store');

    Route::get('transaksi', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::get('transaksi/history', [TransaksiController::class, 'history'])->name('transaksi.history');
    Route::delete('transaksi/{id}', [TransaksiController::class, 'destroy'])->name('transaksi.destroy');

    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('tokos', TokoController::class)->except(['show']);
        Route::resource('suppliers', SupplierController::class)->except(['show']);
    });
});
