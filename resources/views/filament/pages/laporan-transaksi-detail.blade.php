<x-filament::page>
    <h2 class="text-xl font-bold mb-4">Detail Transaksi: {{ $transaksi->id_transaksi }}</h2>

    <p><strong>Tanggal:</strong> {{ $transaksi->tanggal_transaksi }}</p>
    <p><strong>Produk Terjual:</strong> {{ $transaksi->produk_terjual }}</p>
    <p><strong>Total Pendapatan:</strong> Rp{{ number_format($transaksi->total_pendapatan, 2, ',', '.') }}</p>
    <hr class="my-4">

    <h3 class="font-semibold">Daftar Produk:</h3>
    <table class="table-auto w-full mt-2 text-sm">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">SKU</th>
                <th class="px-4 py-2">Nama Produk</th>
                <th class="px-4 py-2">Jumlah</th>
                <th class="px-4 py-2">Harga Satuan</th>
                <th class="px-4 py-2">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksi->detailTransaksi as $detail)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $detail->SKU }}</td>
                    <td class="px-4 py-2">{{ $detail->nama_produk }}</td>
                    <td class="px-4 py-2">{{ $detail->jumlah }}</td>
                    <td class="px-4 py-2">Rp{{ number_format($detail->harga_satuan, 2, ',', '.') }}</td>
                    <td class="px-4 py-2">Rp{{ number_format($detail->total, 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-filament::page>
