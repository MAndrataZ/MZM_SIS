<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class LaporanTransaksiDetail extends Page
{
    public ?Transaksi $transaksi = null;

    protected static ?string $navigationIcon = null;
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.laporan-transaksi-detail';

    // custom loader untuk menerima ID
    public function withId(string $id): self
    {
        $this->transaksi = Transaksi::with('detailTransaksi')->findOrFail($id);
        return $this;
    }

    public function render(): View
    {
        return view('filament.pages.laporan-transaksi-detail', [
            'transaksi' => $this->transaksi,
        ]);
    }
}
