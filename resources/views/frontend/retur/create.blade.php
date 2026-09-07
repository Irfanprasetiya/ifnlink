@extends('layouts.frontend.app')

@section('container')
    <div class="space-y-6 pb-24">

        <div class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.08)] border border-slate-300/80 p-4 sm:p-6">
            <h2 class="text-lg font-extrabold text-slate-800">Ajukan Retur</h2>
            <p class="text-xs text-slate-400 mt-0.5">Pilih produk yang ingin diretur</p>
        </div>

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('retur.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Pilih Produk --}}
            <div
                class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.08)] border border-slate-300/80 p-4 sm:p-6">
                <h3 class="font-bold text-slate-800 mb-3">Tambah Produk</h3>

                {{-- Searchable Dropdown --}}
                <div class="relative" x-data="{ open: false, search: '', selected: null }">
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                        placeholder="Ketik nama produk..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">

                    <div x-show="open" x-cloak
                        class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                        @foreach ($produks as $produk)
                            <button type="button"
                                @click="search = '{{ $produk->voucher->nama_produk }}'; selected = {{ $produk->voucher_id }}; open = false; addProduk({{ $produk->voucher_id }}, '{{ $produk->voucher->nama_produk }}', {{ $produk->voucher->harga_jual }}, {{ $produk->stok }})"
                                x-show="search === '' || '{{ strtolower($produk->voucher->nama_produk) }}'.includes(search.toLowerCase())"
                                class="w-full text-left px-4 py-2.5 hover:bg-slate-50 text-sm">
                                <span class="font-bold">{{ $produk->voucher->nama_produk }}</span>
                                <span class="text-slate-400 text-xs ml-2">Stok: {{ $produk->stok }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- List Produk yang Dipilih --}}
                <div id="selected-items" class="mt-4 space-y-2"></div>
            </div>

            {{-- Alasan --}}
            <div
                class="bg-white rounded-2xl shadow-[0_4px_20px_-4px_rgba(0,0,0,0.08)] border border-slate-300/80 p-4 sm:p-6">
                <label class="text-xs font-bold uppercase text-slate-500">Alasan</label>
                <textarea name="alasan" rows="3" placeholder="Contoh: Barang rusak, expired, salah kirim"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm mt-2"></textarea>
            </div>

            <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition">
                Kirim Pengajuan Retur
            </button>
        </form>
    </div>

    <script>
        let selectedProduk = [];

        function addProduk(id, nama, harga, stok) {
            if (selectedProduk.find(p => p.id === id)) {
                return;
            }

            selectedProduk.push({
                id,
                nama,
                harga,
                stok,
                qty: 1
            });
            renderSelected();
        }

        function renderSelected() {
            const container = document.getElementById('selected-items');

            container.innerHTML = selectedProduk.map((p, index) => `
                <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3">
                    <div class="flex-1">
                        <p class="font-bold text-sm text-slate-800">${p.nama}</p>
                        <p class="text-xs text-slate-400">Harga: Rp ${p.harga.toLocaleString()} | Stok: ${p.stok}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="hidden" name="items[${index}][voucher_id]" value="${p.id}">
                        <input type="number" name="items[${index}][qty]" min="1" max="${p.stok}" value="${p.qty}"
                            onchange="updateQty(${index}, this.value)"
                            class="w-20 bg-white border border-slate-200 rounded-lg px-3 py-2 text-sm text-center">
                        <button type="button" onclick="removeProduk(${index})"
                            class="w-7 h-7 bg-rose-100 text-rose-600 rounded-lg">✕</button>
                    </div>
                </div>
            `).join('');
        }

        function updateQty(index, value) {
            selectedProduk[index].qty = parseInt(value) || 1;
        }

        function removeProduk(index) {
            selectedProduk.splice(index, 1);
            renderSelected();
        }
    </script>
@endsection
