@extends('layouts.app')

@section('title', 'Laporan POS')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        @if (session('success'))
            <div class="mb-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-medium">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- HEADER & FILTER --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800">Laporan POS</h1>
                <p class="text-[11px] sm:text-sm text-slate-500 mt-1">
                    Periode: <span class="font-bold">{{ \Carbon\Carbon::parse($startDate)->translatedFormat('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d M Y') }}</span>
                </p>
            </div>

            <div class="flex flex-col gap-2">
                <form method="GET" class="flex flex-wrap gap-2">
                    <input type="date" name="start_date" value="{{ $startDate }}" class="bg-slate-50 border rounded-lg px-3 py-2 text-sm">
                    <span class="text-slate-400 self-center">s/d</span>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="bg-slate-50 border rounded-lg px-3 py-2 text-sm">
                    <select name="cabang_id" class="bg-slate-50 border rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Cabang</option>
                        @foreach($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Filter</button>
                </form>

                {{-- ✅ Tombol Export --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.pos.laporan.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'cabang_id' => request('cabang_id')]) }}"
                        class="flex-1 bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v1a2 2 0 002 2h14a2 2 0 002-2v-1M3 7v1a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2v0" />
                        </svg>
                        PDF
                    </a>
                    <a href="{{ route('admin.pos.laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate, 'cabang_id' => request('cabang_id')]) }}"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg text-sm font-bold text-center flex items-center justify-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- SUMMARY --}}
        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                <p class="text-xs text-slate-500">Transaksi</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalTransaksi }}</p>
            </div>
            <div class="bg-white rounded-2xl p-4 border border-slate-200">
                <p class="text-xs text-slate-500">Produk Terjual</p>
                <p class="text-2xl font-black text-slate-800">{{ $totalProdukTerjual }}</p>
            </div>
            <div class="col-span-2 lg:col-span-1 bg-white rounded-2xl p-4 border border-blue-200">
                <p class="text-xs text-slate-500">Total Penjualan</p>
                <p class="text-xl font-black text-slate-800">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- TABEL DESKTOP --}}
        <div class="hidden lg:block bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="p-4 border-b bg-slate-50/50">
                <h3 class="font-bold text-slate-800 text-sm">Rincian Transaksi POS</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-white text-[11px] font-extrabold uppercase text-slate-500 border-b">
                        <tr>
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3">ID Transaksi</th>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Produk</th>
                            <th class="px-4 py-3">Kategori</th>
                            <th class="px-4 py-3">Cabang</th>
                            <th class="px-4 py-3">Kasir</th>
                            <th class="px-4 py-3 text-right">Harga Total</th>
                            <th class="px-4 py-3 text-right">Diskon</th>
                            <th class="px-4 py-3 text-right">Total Akhir</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm">
                        @php $no = 1; @endphp
                        @forelse($penjualans as $p)
                            @foreach($p->details as $detail)
                                <tr class="hover:bg-slate-50/50">
                                    <td class="px-4 py-3 text-center text-slate-500">{{ $no++ }}</td>
                                    <td class="px-4 py-3">
                                        @if($loop->first)
                                            <span class="font-mono text-xs font-bold bg-slate-100 px-2 py-1 rounded">{{ $p->kode_transaksi ?? 'TRX-' . str_pad($p->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-500">{{ $loop->first ? $p->created_at->translatedFormat('d M Y H:i') : '' }}</td>
                                    <td class="px-4 py-3 font-bold text-slate-800">{{ $detail->voucher->nama_produk ?? '-' }}</td>
                                    <td class="px-4 py-3 text-xs text-slate-500">{{ $detail->voucher->kategori->nama_kategori ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $p->cabang->nama_cabang ?? '-' }}</td>
                                    <td class="px-4 py-3">{{ $p->user->name ?? '-' }}</td>
                                    <td class="px-4 py-3 text-right">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</td>
                                    <td class="px-4 py-3 text-right text-rose-500 text-xs font-bold">
                                        @if($p->diskon > 0 && $loop->first) -Rp {{ number_format($p->diskon, 0, ',', '.') }} @else - @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        @if($loop->first)
                                            <span class="bg-emerald-50 text-emerald-700 font-extrabold px-2 py-1 rounded">Rp {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        @if($loop->first)
                                            <div class="flex justify-center gap-1.5">
                                                <button onclick="openEditModal({{ $p->id }})" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg" title="Edit">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </button>
                                                <form action="{{ route('admin.pos.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="11" class="text-center py-16 text-slate-500">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- MOBILE CARDS --}}
        <div class="lg:hidden space-y-3">
            @forelse($penjualans as $p)
                <div class="bg-white border rounded-xl p-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-mono text-xs font-bold">{{ $p->kode_transaksi ?? 'TRX-' . $p->id }}</span>
                        <span class="text-xs text-slate-500">{{ $p->created_at->translatedFormat('d M H:i') }}</span>
                    </div>
                    <div class="space-y-2 mb-3">
                        @foreach($p->details as $d)
                            <div class="flex justify-between text-sm">
                                <div>
                                    <p class="font-bold">{{ $d->voucher->nama_produk }}</p>
                                    <p class="text-xs text-slate-400">{{ $d->voucher->kategori->nama_kategori ?? '-' }} | {{ $d->qty }}x</p>
                                </div>
                                <span>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t pt-2 flex justify-between">
                        <span class="font-bold">Total</span>
                        <span class="font-black text-emerald-600">Rp {{ number_format($p->total_setelah_diskon, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex gap-2 mt-3">
                        <button onclick="openEditModal({{ $p->id }})" class="flex-1 bg-amber-50 text-amber-600 py-2 rounded-lg text-sm font-bold">Edit</button>
                        <form action="{{ route('admin.pos.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus?')" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-rose-50 text-rose-600 py-2 rounded-lg text-sm font-bold">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-center text-slate-500 py-10">Belum ada data</p>
            @endforelse
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="edit-modal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/50 p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md max-h-[85vh] flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b flex justify-between items-center bg-slate-50/50">
                <div>
                    <h3 class="font-bold text-slate-800">Edit Transaksi</h3>
                    <p class="text-xs font-mono text-slate-500">TRX: <span id="edit-kode"></span></p>
                </div>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-rose-500 text-xl">✕</button>
            </div>

            <form id="edit-form" method="POST" onsubmit="return submitEdit(event)" class="flex flex-col flex-1 overflow-hidden">
                @csrf @method('PUT')

                <div class="p-5 overflow-y-auto flex-1">
                    <p class="text-xs font-bold text-slate-500 uppercase mb-2">Daftar Produk</p>
                    <div id="edit-items" class="space-y-3"></div>
                </div>

                <div class="px-5 py-4 border-t bg-slate-50">
                    <div class="flex justify-between text-sm mb-2">
                        <span class="text-slate-500">Subtotal</span>
                        <span id="edit-subtotal" class="font-bold">Rp 0</span>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-500 uppercase">Diskon</label>
                        <input type="text" id="edit-diskon" name="diskon" oninput="formatDiskonInput(this); updateEditTotal();"
                            class="w-full bg-white border rounded-lg px-3 py-2 text-sm font-bold text-rose-500 text-right" placeholder="0">
                    </div>
                    <div class="flex justify-between border-t pt-2 mt-2">
                        <span class="font-bold">Total</span>
                        <span id="edit-total" class="font-black text-emerald-600">Rp 0</span>
                    </div>
                    <button type="submit" id="btn-simpan" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl mt-3 flex items-center justify-center gap-2">
                        <svg id="btn-simpan-spinner" class="hidden animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="btn-simpan-text">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentPenjualanId = null;
        let editItems = [];
        let allVouchers = @json($allVouchers ?? []);

        function formatDiskonInput(input) {
            let value = input.value.replace(/[^\d]/g, '');
            if (value) input.value = parseInt(value).toLocaleString('id-ID');
        }

        function getDiskonValue() {
            return parseInt(document.getElementById('edit-diskon').value.replace(/[^\d]/g, '')) || 0;
        }

        function openEditModal(id) {
            currentPenjualanId = id;
            fetch(`/admin/pos/${id}/edit`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('edit-kode').textContent = data.kode_transaksi || `TRX-${id}`;
                    document.getElementById('edit-diskon').value = data.diskon > 0 ? Number(data.diskon).toLocaleString('id-ID') : '';
                    document.getElementById('edit-form').action = `/admin/pos/${id}/update`;
                    editItems = data.details;
                    renderEditItems();
                    updateEditTotal();
                    document.getElementById('edit-modal').classList.remove('hidden');
                });
        }

        function renderEditItems() {
            const container = document.getElementById('edit-items');
            container.innerHTML = editItems.map((item, index) => `
            <div class="bg-white border border-slate-200 rounded-xl p-3 shadow-sm">
                <input type="hidden" name="items[${index}][detail_id]" value="${item.id}">
                <input type="hidden" name="items[${index}][voucher_id]" id="voucher-${index}" value="${item.voucher_id}">
                
                <div class="relative" x-data="{ open: false, search: '${item.nama_produk}' }">
                    <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Produk</p>
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                        class="w-full bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm font-bold text-slate-800">
                    <div x-show="open" x-cloak class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-lg shadow-lg max-h-36 overflow-y-auto">
                        @foreach($allVouchers ?? [] as $v)
                            <button type="button"
                                @click="search = '{{ $v->nama_produk }}'; open = false; ubahProduk(${index}, {{ $v->id }}, '{{ $v->nama_produk }}', {{ $v->harga_jual }})"
                                x-show="search === '' || '{{ strtolower($v->nama_produk) }}'.includes(search.toLowerCase())"
                                class="w-full text-left px-3 py-2 hover:bg-slate-50 text-sm">
                                {{ $v->nama_produk }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <p class="text-xs text-slate-400 mt-1">Harga: Rp <span class="harga-text">${Number(item.harga_satuan).toLocaleString()}</span></p>

                <div class="flex items-center justify-between mt-2">
                    <span class="text-xs font-bold text-slate-500">Qty</span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" onclick="changeQty(${index}, -1)" class="w-7 h-7 bg-slate-100 rounded-lg font-bold">-</button>
                        <input type="number" name="items[${index}][qty]" value="${item.qty}" min="1" oninput="setQty(${index}, this.value)" class="w-12 text-center border rounded-lg py-1 text-sm font-bold">
                        <button type="button" onclick="changeQty(${index}, 1)" class="w-7 h-7 bg-blue-50 text-blue-600 rounded-lg font-bold">+</button>
                    </div>
                </div>
            </div>
        `).join('');
        }

        function ubahProduk(index, id, nama, harga) {
            editItems[index].voucher_id = id;
            editItems[index].nama_produk = nama;
            editItems[index].harga_satuan = harga;
            document.getElementById(`voucher-${index}`).value = id;
            renderEditItems();
            updateEditTotal();
        }

        function changeQty(index, delta) {
            const newQty = editItems[index].qty + delta;
            if (newQty >= 1) {
                editItems[index].qty = newQty;
                renderEditItems();
                updateEditTotal();
            }
        }

        function setQty(index, value) {
            editItems[index].qty = parseInt(value) || 1;
            updateEditTotal();
        }

        function updateEditTotal() {
            const subtotal = editItems.reduce((sum, item) => sum + (item.harga_satuan * item.qty), 0);
            const diskon = getDiskonValue();
            const total = subtotal - diskon;
            document.getElementById('edit-subtotal').textContent = 'Rp ' + subtotal.toLocaleString();
            document.getElementById('edit-total').textContent = total >= 0 ? 'Rp ' + total.toLocaleString() : 'Rp 0';
        }

        function submitEdit(event) {
            const btn = document.getElementById('btn-simpan');
            btn.disabled = true;
            btn.classList.add('opacity-70');
            document.getElementById('btn-simpan-spinner').classList.remove('hidden');
            document.getElementById('btn-simpan-text').textContent = 'Menyimpan...';
            return true;
        }

        function closeEditModal() {
            document.getElementById('edit-modal').classList.add('hidden');
        }
    </script>
@endsection