<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\LaporanTransaksiDetail;
use App\Filament\Pages\DetailTransaksi;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin/login');

Route::get('/admin/laporan-transaksi/{id}', function ($id) {
    return (new LaporanTransaksiDetail())->withId($id)->render();
})->name('filament.pages.laporan-transaksi-detail');

Route::get('/admin/detail-transaksi/{id}', [DetailTransaksi::class, '__invoke'])
    ->name('filament.admin.pages.detail-transaksi');
