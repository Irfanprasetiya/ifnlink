@extends('layouts.app')

@section('title', 'Data Barang Masuk')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 relative mt-4 sm:mt-7">

        {{-- HEADER & FILTER --}}
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-slate-200/80 p-3.5 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-3">
            <div>
                <h1 class="text-base sm:text-xl font-extrabold text-slate-800">Data Barang Masuk</h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1">Riwayat stok masuk produk</p>
            </div>

            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <form method="GET" class="flex flex-wrap gap-2">
                    <select name="cabang_id" class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                        <option value="">Semua Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}" {{ request('cabang_id') == $cabang->id ? 'selected' : '' }}>
                                {{ $cabang->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                        class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-sm">
                    <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-sm font-bold">Filter</button>
                    <a href="{{ route('barang_masuk.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 px-4 py-2 rounded-lg text-sm font-bold">Reset</a>
                </form>

                <button data-modal-toggle="create-modal" data-modal-target="create-modal"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold">
                    + Barang Masuk
                </button>
            </div>
        </div>

        {{-- ALERT --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- TABEL --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 text-xs font-bold uppercase text-slate-500">
                        <tr>
                            <th class="px-5 py-4 text-center">No</th>
                            <th class="px-5 py-4">Tanggal</th>
                            <th class="px-5 py-4">Produk</th>
                            <th class="px-5 py-4">Cabang</th>
                            <th class="px-5 py-4 text-center">Qty</th>
                            <th class="px-5 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @php $no = 1; @endphp
                        @forelse ($barangMasuks as $item)
                            <tr class="hover:bg-slate-50/50">
                                <td class="px-5 py-3.5 text-center text-slate-500">{{ $no++ }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $item->tanggal->format('d/m/Y') }}</td>
                                <td class="px-5 py-3.5 font-bold text-slate-800">{{ $item->produk_konter->voucher->nama_produk ?? '-' }}</td>
                                <td class="px-5 py-3.5">{{ $item->produk_konter->cabang->nama_cabang ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-center font-bold text-blue-600">{{ $item->qty }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex justify-center gap-2">
                                        <button data-modal-toggle="edit-modal-{{ $item->id }}"
                                            class="p-1.5 bg-amber-50 text-amber-600 rounded-lg" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                        <form action="{{ route('barang_masuk.destroy', $item->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg" title="Hapus">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            {{-- Modal Edit --}}
                            <form method="POST" action="{{ route('barang_masuk.update', $item->id) }}">
                                @csrf @method('PUT')
                                <div id="edit-modal-{{ $item->id }}"
                                    class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
                                    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
                                        <h3 class="text-lg font-extrabold text-slate-800 mb-4">Edit Barang Masuk</h3>

                                        <div class="mb-3">
                                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Produk & Cabang</label>
                                            <select name="produk_konter_id" required class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm">
                                                @foreach ($produkKonters as $pk)
                                                    <option value="{{ $pk->id }}" {{ $item->produk_konter_id == $pk->id ? 'selected' : '' }}>
                                                        {{ $pk->voucher->nama_produk }} - {{ $pk->cabang->nama_cabang }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Tanggal</label>
                                            <input type="date" name="tanggal" value="{{ $item->tanggal->format('Y-m-d') }}"
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm" required>
                                        </div>

                                        <div class="mb-3">
                                            <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Qty</label>
                                            <input type="number" name="qty" value="{{ $item->qty }}" min="1"
                                                class="w-full bg-slate-50 border rounded-xl px-4 py-3 text-sm" required>
                                        </div>

                                        <div class="flex justify-end gap-2">
                                            <button type="button" data-modal-toggle="edit-modal-{{ $item->id }}"
                                                class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2.5 rounded-lg text-sm">Batal</button>
                                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg text-sm">Update</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @empty
                            <tr>
                                <td colspan="6" class="py-16 text-center text-slate-500">Belum ada data barang masuk</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Tambah --}}
    <form method="POST" action="{{ route('barang_masuk.store') }}" id="form-tambah" onsubmit="return submitTambah(event)">
        @csrf
        <div id="create-modal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md">
                <h3 class="text-lg font-extrabold text-slate-800 mb-4">Tambah Barang Masuk</h3>

                {{-- Nama Produk --}}
                <div class="mb-3 relative" x-data="{ open: false, search: '' }">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Nama Produk</label>
                    <input type="text" x-model="search" @focus="open = true" @click.away="open = false"
                        placeholder="Ketik nama produk..."
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                    <input type="hidden" name="voucher_id" id="voucher_id" required>

                    <div x-show="open" x-cloak
                        class="absolute z-20 mt-1 w-full bg-white border border-slate-200 rounded-xl shadow-lg max-h-40 overflow-y-auto">
                        @foreach ($vouchers as $voucher)
                            <button type="button"
                                @click="search = '{{ $voucher->nama_produk }}'; open = false; document.getElementById('voucher_id').value = '{{ $voucher->id }}'"
                                x-show="search === '' || '{{ strtolower($voucher->nama_produk) }}'.includes(search.toLowerCase())"
                                class="w-full text-left px-4 py-2.5 hover:bg-slate-50 text-sm">
                                <span class="font-bold">{{ $voucher->nama_produk }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Qty --}}
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Qty</label>
                    <input type="number" name="qty" id="qty" min="1" required placeholder="0"
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                </div>

                {{-- Cabang --}}
                <div class="mb-3">
                    <label class="block text-xs font-bold uppercase text-slate-500 mb-1">Cabang</label>
                    <select name="cabang_id" id="cabang_id" required
                        class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm">
                        <option value="">Pilih Cabang</option>
                        @foreach ($cabangs as $cabang)
                            <option value="{{ $cabang->id }}">{{ $cabang->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" data-modal-toggle="create-modal"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold px-4 py-2.5 rounded-lg text-sm">Batal</button>
                    <button type="submit" id="btn-simpan-tambah"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-5 py-2.5 rounded-lg text-sm flex items-center gap-2">
                        <svg id="spinner-tambah" class="hidden animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        <span id="text-simpan-tambah">Simpan</span>
                    </button>
                </div>
            </div>
        </div>
    </form>

    {{-- Script Modal --}}
    <script>
        function submitTambah(event) {
            const voucherId = document.getElementById('voucher_id').value;
            const qty = document.getElementById('qty').value;
            const cabangId = document.getElementById('cabang_id').value;

            if (!voucherId) {
                event.preventDefault();
                alert('Pilih produk terlebih dahulu!');
                return false;
            }
            if (!qty || qty < 1) {
                event.preventDefault();
                alert('Qty minimal 1!');
                return false;
            }
            if (!cabangId) {
                event.preventDefault();
                alert('Pilih cabang!');
                return false;
            }

            const btn = document.getElementById('btn-simpan-tambah');
            const spinner = document.getElementById('spinner-tambah');
            const text = document.getElementById('text-simpan-tambah');

            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            spinner.classList.remove('hidden');
            text.textContent = 'Menyimpan...';

            return true;
        }

        // Modal logic
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('[data-modal-toggle]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const target = this.getAttribute('data-modal-toggle');
                    const modal = document.getElementById(target);
                    if (modal) {
                        const isOpen = !modal.classList.contains('hidden');
                        if (isOpen) {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        } else {
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        }
                    }
                });
            });

            document.querySelectorAll('[id^="create-modal"], [id^="edit-modal-"]').forEach(modal => {
                modal.addEventListener('click', function(e) {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            });
        });
    </script>
@endsection