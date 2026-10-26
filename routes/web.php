<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureRole;
use Illuminate\Http\Request;

Route::get('/', fn () => redirect()->route('dashboard'));

// ---- Tamu (belum login) ----
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
});

// ---- Harus login (admin & staff & customer) ----
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // ---- Khusus admin (didaftarkan SEBELUM products.show agar /products/create tidak tertangkap {product}) ----
    Route::middleware(EnsureRole::class . ':admin')->group(function () {
        Route::resource('products', ProductController::class)->except(['index', 'show']);
        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('suppliers', SupplierController::class)->except('show');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
    });

    // Admin & staff boleh melihat produk & manajemen inventaris dasar
    Route::resource('products', ProductController::class)->only(['index', 'show']);
    Route::resource('categories', CategoryController::class)->only(['index', 'show']);
    Route::resource('suppliers', SupplierController::class)->only(['index', 'show']);

    // Barang masuk / keluar / histori (admin & staff)
    Route::get('/stock/history', [StockController::class, 'history'])->name('stock.history');
    Route::get('/stock/{type}', [StockController::class, 'create'])->whereIn('type', ['in', 'out'])->name('stock.create');
    Route::post('/stock/{type}', [StockController::class, 'store'])->whereIn('type', ['in', 'out'])->name('stock.store');

    // ---- Fitur Pembelian & Payment Gateway Customer (UTS) ----
    Route::get('/customer/buy', function () {
        $products = \App\Models\Product::all();
        return view('customer.buy', compact('products'));
    })->name('customer.buy');

    Route::post('/customer/pay', function (Request $request) {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'payment_method' => 'required'
        ]);

        $product = \App\Models\Product::find($request->product_id);

        if ($product && $product->stock >= $request->quantity) {
            // Kurangi stok produk secara otomatis saat dibeli
            $product->decrement('stock', $request->quantity);

            return redirect()->route('customer.buy')->with('success', 'Pembayaran via ' . strtoupper($request->payment_method) . ' Berhasil! Pesanan ' . $product->name . ' sedang diproses.');
        }

        return redirect()->route('customer.buy')->with('error', 'Gagal! Stok barang tidak mencukupi.');
    })->name('customer.pay');
});