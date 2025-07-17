<x-filament::page>
    <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
        <input type="date" wire:model="tanggal_transaksi" class="mt-1 block w-full max-w-xs rounded-md border-gray-300 shadow-sm" />
    </div>

    <form wire:submit.prevent="addToList" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Produk</label>
                <select wire:model="form.SKU" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih SKU --</option>
                    @foreach(\App\Models\Produk::all() as $produk)
                        <option value="{{ $produk->SKU }}">{{ $produk->SKU }} - {{ $produk->nama_produk }}</option>
                    @endforeach
                </select>
            </div>

            {{-- <div>
                <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" wire:model="form.nama_produk" disabled />
            </div> --}}

            <div>
                <label class="block text-sm font-medium text-gray-700">Dijual Ke</label>
                <div class="mt-1 flex gap-2">
                    <button type="button"
                        wire:click="setJenisPenjualan('retail')"
                        class="px-4 py-1 rounded text-white 
                            {{ $form['dijual_ke'] === 'retail' ? 'bg-primary-600 hover:bg-primary-700' : 'bg-gray-300 text-black' }}">
                        Retail
                    </button>
                    <button type="button"
                        wire:click="setJenisPenjualan('reseller')"
                        class="px-4 py-1 rounded text-white 
                            {{ $form['dijual_ke'] === 'reseller' ? 'bg-primary-600 hover:bg-primary-700' : 'bg-gray-300 text-black' }}">
                        Reseller
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Harga Satuan</label>
                <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" wire:model="form.harga_satuan" disabled />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Jumlah</label>
                <input
                    type="number"
                    wire:model.debounce.300ms="form.jumlah"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $form['harga_satuan'] == 0 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                    {{ $form['harga_satuan'] == 0 ? 'disabled' : '' }}
                />
                
                @if ($form['harga_satuan'] == 0)
                    <p class="text-xs text-red-500 mt-1">Pilih produk dan jenis penjualan terlebih dahulu.</p>
                @endif
            </div>

            {{-- <div>
                <label class="block text-sm font-medium text-gray-700">Total</label>
                <input type="text" wire:model="form.total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" disabled />
            </div> --}}
        </div>

        <div class="mt-4">
            <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">
                Masukkan ke List
            </button>
        </div>
    </form>

    <hr class="my-6" />

    <h3 class="text-lg font-semibold mb-2">Detail Transaksi</h3>
    <table class="table-auto w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="px-2 py-1">SKU</th>
                <th class="px-2 py-1">Nama Produk</th>
                <th class="px-2 py-1">Jumlah</th>
                <th class="px-2 py-1">Harga Satuan</th>
                <th class="px-2 py-1">Total</th>
                <th class="px-2 py-1">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($list as $index => $item)
                <tr class="text-center">
                    <td class="px-2 py-1">{{ $item['SKU'] }}</td>
                    <td class="px-2 py-1">{{ $item['nama_produk'] }}</td>
                    <td class="px-2 py-1">{{ $item['jumlah'] }}</td>
                    <td class="px-2 py-1">Rp {{ number_format($item['harga_satuan'], 0, ',', '.') }}</td>
                    <td class="px-2 py-1">Rp {{ number_format($item['total'], 0, ',', '.') }}</td>
                    <td class="px-2 py-1">
                        <button type="button" wire:click="removeFromList({{ $index }})" class="text-red-600 border border-red-600 hover:bg-red-50 px-3 py-1 rounded">
                            Hapus
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr class="my-4" />

    <div class="flex justify-between items-center">
        <div>
            <p><strong>Produk Terjual:</strong> {{ collect($list)->sum('jumlah') }} pcs</p>
            <p><strong>Total Pendapatan:</strong> Rp {{ number_format(collect($list)->sum('total'), 0, ',', '.') }}</p>
        </div>
        <button wire:click="prosesTransaksi" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded">
            Proses Transaksi
        </button>
    </div>
</x-filament::page>