<?php

namespace App\Filament\Resources\ProduksiResource\Pages;

use App\Filament\Resources\ProduksiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProduksi extends CreateRecord
{
    protected static string $resource = ProduksiResource::class;

    // protected function getRedirectUrl():}{


    // }

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     // Tambahkan stok ke produk
    //     $produk = \App\Models\Produk::where('SKU', $data['SKU'])->first();

    //     if ($produk) {
    //         $produk->stok += $data['jumlah_produksi'];
    //         $produk->save();

    //         // Hitung total modal = modal per item * jumlah produksi
    //         $data['total_modal'] = $produk->modal * $data['jumlah_produksi'];
    //     }

    //     return $data;
    // }

}
