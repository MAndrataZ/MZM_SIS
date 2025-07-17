<?php

namespace App\Filament\Resources\TransaksiFormResource\Pages;

use Filament\Actions;
use App\Models\Produk;
use App\Models\DetailTransaksi;
use Filament\Pages\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\TransaksiFormResource;

class CreateTransaksiForm extends CreateRecord
{
    protected static string $resource = TransaksiFormResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $produkTerjual = 0;
        $totalPendapatan = 0;

        $detailTransaksi = $this->form->getRawState()['detailTransaksi'] ?? [];

        if (empty($detailTransaksi)) {
            throw new \Exception("Minimal satu produk harus ditambahkan dalam transaksi.");
        }

        foreach ($detailTransaksi as $item) {
            $produk = Produk::find($item['SKU']);
            if (!$produk) {
                throw new \Exception("Produk dengan SKU {$item['SKU']} tidak ditemukan.");
            }

            if ($produk->stok < $item['jumlah']) {
                throw new \Exception("Stok produk '{$produk->nama_produk}' tidak mencukupi. Sisa stok: {$produk->stok}");
            }

            $produkTerjual += intval($item['jumlah']);
            $totalPendapatan += floatval($item['total']);
        }

        $data['produk_terjual'] = $produkTerjual;
        $data['total_pendapatan'] = $totalPendapatan;

        return $data;
    }

    protected function afterCreate(): void
    {
        $transaksi = $this->record;
        $detailTransaksi = $this->form->getRawState()['detailTransaksi'] ?? [];

        foreach ($detailTransaksi as $item) {
            $produk = Produk::find($item['SKU']);

            if (!$produk) {
                throw new \Exception("Produk dengan SKU {$item['SKU']} tidak ditemukan saat afterCreate.");
            }

            DetailTransaksi::create([
                'id_transaksi' => $transaksi->id,
                'SKU' => $item['SKU'],
                'nama_produk' => $produk->nama_produk, // dipastikan produk ada
                'dijual_ke' => $item['dijual_ke'] ?? null,
                'harga_satuan' => $item['harga_satuan'],
                'jumlah' => $item['jumlah'],
                'total' => $item['total'],
            ]);

            // Kurangi stok
            $produk->stok -= $item['jumlah'];
            $produk->save();
        }

    }

    protected function getFormActions(): array
    {
        return [
            Action::make('simpan')
                ->label('Simpan Transaksi')
                ->submit('create')
                ->color('success'),

            Action::make('batal')
                ->label('Batal')
                ->color('gray')
                ->action(fn () => $this->form->fill()),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('create');
    }
}
