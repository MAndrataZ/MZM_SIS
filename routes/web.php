<?php

use Illuminate\Support\Facades\Route;
use App\Filament\Pages\LaporanTransaksiDetail;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::redirect('/', '/admin/login');

Route::get('/admin/laporan-transaksi/{id}', function ($id) {
    return (new LaporanTransaksiDetail())->withId($id)->render();
})->name('filament.pages.laporan-transaksi-detail');

