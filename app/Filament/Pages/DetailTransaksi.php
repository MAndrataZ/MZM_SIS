<?php

namespace App\Filament\Pages;

use App\Models\Transaksi;
use Filament\Pages\Page;

class DetailTransaksi extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static string $view = 'filament.pages.detail-transaksi';

    public ?Transaksi $transaksi = null;

    public static function getRouteParameters(): array
    {
        return ['id'];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public function mount(): void
    {
        $id = request()->route('id');

        $this->transaksi = \App\Models\Transaksi::with('detailTransaksi')
            ->where('id_transaksi', $id)
            ->firstOrFail();
    }
}
