<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PegawaiController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

Route::get('portofolio', [ProdukController::class, 'portofolio'])->name('portofolio');
Route::group([
    'prefix' => 'produk'
], function () {

    Route::get('/', [ProdukController::class, 'index'])->name('produk');
    Route::get('/{produk:slug}', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('kategori/{kategori:slug}', [ProdukController::class, 'byKategori'])->name('produk.kategori');
});

Route::group([
    'prefix' => '',
    'middleware' => ['auth'],

], function () {

    Route::post('/{produk:slug}', [ProdukController::class, 'cart'])->name('produk.cart');
    Route::get('/sales/{sales:id}', [SalesController::class, 'index'])->name('sales');
    Route::post('/sales/{sales:id}', [SalesController::class, 'create'])->name('sales.post');
});

Route::group([
    'prefix' => '',
    'middleware' => ['auth', 'role:admin'],

], function () {

    // Route::get('/admin/pegawai/pdf', [PegawaiController::class, 'generatePDF'])->name('pegawai.pdf');
    // Route::get('/admin/penjualan/pdf', [SalesController::class, 'generatePDF'])->name('sales.pdf');
    // Route::get('/admin/produk/pdf', [ProdukController::class, 'generatePDFProduk'])->name('produk.pdf');
    // Route::get('/admin/stok/pdf', [ProdukController::class, 'generatePDFStok'])->name('stok.pdf');

    Route::get('/admin/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::post('/admin/laporan', [LaporanController::class, 'generatePDF'])->name('laporan.generatePDF');
});