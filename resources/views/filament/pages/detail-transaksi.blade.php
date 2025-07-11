<x-filament::page>

    <x-filament::section>
        <x-slot name="heading">
            <div class="flex justify-between items-center">
                <div>
                    Detail Transaksi: {{ $transaksi->id_transaksi }}
                </div>
                <div>
                  <a href="{{ url()->previous() }}"
                    class="inline-flex items-center px-4 py-2 bg-primary-500 text-white rounded">
                    Kembali
                  </a>
                </div>            
            </div>
        </x-slot>

        


        @foreach ($transaksi->detailTransaksi ?? [] as $detail)
        <x-filament::fieldset  label="Produk ke-{{ $loop->iteration }}">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">SKU</h3>
                        <p class="text-lg">{{ $detail->SKU }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">Nama Produk</h3>
                        <p class="text-lg">{{ $detail->nama_produk }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">Dijual Ke</h3>
                        <p class="text-lg capitalize">{{ $detail->dijual_ke }}</p>
                    </div>
                </div>


                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">Harga Satuan</h3>
                        <p class="text-lg">Rp {{ number_format($detail->harga_satuan, 2, ',', '.') }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">Jumlah</h3>
                        <p class="text-lg">{{ $detail->jumlah }}</p>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-500 dark:text-gray-400">Total</h3>
                        <p class="text-lg">Rp {{ number_format($detail->total, 2, ',', '.') }}</p>
                    </div>
                </div>
            </div>
        </x-filament::fieldset>
        @endforeach

    </x-filament::section>

</x-filament::page>