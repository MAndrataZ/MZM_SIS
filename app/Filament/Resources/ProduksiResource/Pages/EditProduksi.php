<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Models\Produk;
use App\Models\Produksi;
use Filament\Forms;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProduksiResource;

class EditProduksi extends EditRecord
{
    protected static string $resource = ProduksiResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ambil data produk berdasarkan SKU
        $produk = Produk::find($data['SKU']);

        $data['nama_produk'] = $produk->nama_produk ?? '';
        $data['stok'] = $produk->stok ?? 0;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Simpan perubahan hanya ke stok produk
        $produk = Produk::find($data['SKU']);

        if ($produk) {
            $produk->stok = $data['stok'];
            $produk->save();
        }

        // Jangan update tabel produksi sama sekali
        return [];
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('SKU')
                ->disabled()
                ->required(),

            Forms\Components\TextInput::make('nama_produk')
                ->disabled()
                ->label('Nama Produk'),

            Forms\Components\TextInput::make('stok')
                ->label('Stok')
                ->numeric()
                ->required(),
        ];
    }

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\Resources\ProduksiResource\Widgets\HistoryProduksiTable::class,
        ];
    }
}
