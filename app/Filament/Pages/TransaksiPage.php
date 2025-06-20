<?php

namespace App\Filament\Pages;

use App\Models\Produk;
use App\Models\Transaksi;
use Filament\Pages\Page;

class TransaksiPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static string $view = 'filament.pages.transaksi-page';
    protected static ?string $navigationGroup = 'Kelola Produk';
    protected static ?int $navigationSort = 99;
    protected static ?string $title = 'Transaksi';

    public $form = [
        'SKU' => '',
        'nama_produk' => '',
        'dijual_ke' => '',
        'harga_satuan' => 0,
        'jumlah' => 0,
        'total' => 0,
    ];

    public $list = [];

    public $tanggal_transaksi;

    public function updatedFormSKU($value)
    {
        $produk = Produk::find($value);
        if ($produk) {
            $this->form['nama_produk'] = $produk->nama_produk;

            if ($this->form['dijual_ke']) {
                $this->form['harga_satuan'] = $this->form['dijual_ke'] === 'retail'
                    ? $produk->harga_retail
                    : $produk->harga_reseller;
            }

            if ($this->form['jumlah']) {
                $this->updateTotal();
            }
        }
    }

    public function setJenisPenjualan($jenis)
    {
        $this->form['dijual_ke'] = $jenis;

        $produk = Produk::find($this->form['SKU']);
        if ($produk) {
            $this->form['harga_satuan'] = $jenis === 'retail'
                ? $produk->harga_retail
                : $produk->harga_reseller;
        }

        $this->updateTotal();
    }

    public function updatedFormJumlah($value)
    {
        $this->updateTotal();
    }

    public function updateTotal()
    {
        $jumlah = (int) ($this->form['jumlah'] ?? 0);
        $harga = (float) ($this->form['harga_satuan'] ?? 0);
        $this->form['total'] = $jumlah * $harga;
    }

    public function addToList()
    {
        if (!$this->form['SKU'] || !$this->form['dijual_ke'] || $this->form['jumlah'] <= 0) {
            $this->dispatch('notify', type: 'danger', message: 'Lengkapi semua data sebelum menambahkan.');
            return;
        }

        $this->list[] = $this->form;

        $this->form = [
            'SKU' => '',
            'nama_produk' => '',
            'dijual_ke' => '',
            'harga_satuan' => 0,
            'jumlah' => 0,
            'total' => 0,
        ];
    }

    public function removeFromList($index)
    {
        unset($this->list[$index]);
        $this->list = array_values($this->list);
    }

    public function prosesTransaksi()
    {
        if (empty($this->list)) {
            $this->dispatch('notify', type: 'danger', message: 'Tidak ada data untuk diproses.');
            return;
        }

        if (!$this->tanggal_transaksi) {
            $this->dispatch('notify', type: 'danger', message: 'Tanggal transaksi harus diisi.');
            return;
        }

        $idTransaksi = 'TRX' . str_pad((Transaksi::count() + 1), 3, '0', STR_PAD_LEFT);
        $totalProduk = collect($this->list)->sum('jumlah');
        $totalPendapatan = collect($this->list)->sum('total');

        $transaksi = Transaksi::create([
            // 'id_transaksi' => $idTransaksi,
            'tanggal_transaksi' => $this->tanggal_transaksi,
            'produk_terjual' => $totalProduk,
            'total_pendapatan' => $totalPendapatan,
            // 'id_pegawai' => Auth::user()->id_pegawai,
        ]);

        foreach ($this->list as $item) {
            $transaksi->detailTransaksi()->create($item);

            $produk = Produk::find($item['SKU']);
            if ($produk) {
                $produk->stok -= $item['jumlah'];
                if ($produk->stok < 0) $produk->stok = 0;
                $produk->save();
            }
        }

        $this->list = [];
        $this->form = [
            'SKU' => '',
            'nama_produk' => '',
            'dijual_ke' => '',
            'harga_satuan' => 0,
            'jumlah' => 0,
            'total' => 0,
        ];
        $this->tanggal_transaksi = null;

        $this->dispatch('notify', type: 'success', message: 'Transaksi berhasil diproses dan stok diperbarui.');
    }
}
