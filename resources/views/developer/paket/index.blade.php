@extends('layouts.app')

@section('title', 'Kelola Paket Langganan')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12">

        {{-- Header Page --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                    Kelola Paket Langganan
                </h1>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">
                    Atur pilihan paket harga, batas maksimal user, dan fitur yang tersedia untuk platform.
                </p>
            </div>
            <button type="button" onclick="document.getElementById('modalTambahPaket').classList.remove('hidden')"
                class="inline-flex items-center justify-center gap-1.5 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-xs sm:text-sm font-semibold shadow-md shadow-blue-500/20 transition-all">
                <span>➕</span> Tambah Paket
            </button>
        </div>

        {{-- Session Success Alert --}}
        @if (session('success'))
            <div
                class="flex items-center gap-2.5 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl text-xs sm:text-sm font-medium shadow-sm">
                <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Grid Kartu Paket --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse ($plans as $plan)
                <div
                    class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-6 relative flex flex-col justify-between hover:shadow-md transition-all">
                    <div>
                        {{-- Header Card (Nama & Status) --}}
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-slate-900 tracking-tight">{{ $plan->nama_paket }}</h3>
                            <span
                                class="text-xs font-bold px-3 py-1 rounded-full border {{ $plan->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                {{ $plan->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>

                        {{-- Harga & Batasan User --}}
                        <div class="mb-5 pb-4 border-b border-slate-100">
                            <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight mb-1">
                                Rp {{ number_format($plan->harga, 0, ',', '.') }}
                                <span class="text-xs font-normal text-slate-400">/ bulan</span>
                            </div>
                            <p class="text-xs font-medium text-slate-500">
                                👥 {{ $plan->max_user ? 'Hingga ' . $plan->max_user . ' user' : 'User tak terbatas' }}
                            </p>
                        </div>

                        {{-- Daftar Fitur --}}
                        @if ($plan->fitur && count($plan->fitur) > 0)
                            <div class="mb-6">
                                <span class="text-[11px] uppercase tracking-wider font-bold text-slate-400 block mb-3">Fitur
                                    Termasuk:</span>
                                <ul class="space-y-2">
                                    @foreach ($plan->fitur as $f)
                                        <li class="text-xs sm:text-sm text-slate-600 flex items-start gap-2.5 font-medium">
                                            <div
                                                class="w-4 h-4 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 mt-0.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                            <span>{{ $f }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- Tombol Aksi Bawah --}}
                    <div
                        class="grid grid-cols-3 gap-1.5 pt-4 border-t border-slate-100 mt-auto bg-slate-50/50 -mx-6 -mb-6 p-4 rounded-b-2xl">
                        <button type="button" onclick='openEditModal(@json($plan))'
                            class="text-xs font-bold text-blue-600 hover:bg-blue-50 rounded-xl py-2 px-1 transition text-center">
                            Edit
                        </button>

                        <form action="{{ route('developer.paket.toggle', $plan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                class="w-full text-xs font-bold text-slate-600 hover:bg-slate-100 rounded-xl py-2 px-1 transition text-center">
                                {{ $plan->is_active ? 'Nonaktif' : 'Aktifkan' }}
                            </button>
                        </form>

                        <form action="{{ route('developer.paket.destroy', $plan->id) }}" method="POST"
                            onsubmit="return confirm('Yakin hapus paket ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="w-full text-xs font-bold text-rose-600 hover:bg-rose-50 rounded-xl py-2 px-1 transition text-center">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-3 bg-white rounded-2xl border border-slate-200/80 p-12 text-center text-slate-400 text-sm font-medium">
                    Belum ada paket langganan. Klik <span class="text-blue-600 font-semibold">"+ Tambah Paket"</span> untuk
                    membuat paket baru.
                </div>
            @endforelse
        </div>

        {{-- Modal Tambah Paket --}}
        <div id="modalTambahPaket" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Tambah Paket Baru</h3>
                    <button type="button" onclick="document.getElementById('modalTambahPaket').classList.add('hidden')"
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                </div>

                <form action="{{ route('developer.paket.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                            Paket</label>
                        <input type="text" name="nama_paket" required placeholder="Contoh: Paket Pro"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Harga per
                            Bulan (Rp)</label>
                        <input type="number" name="harga" required min="0" placeholder="Contoh: 150000"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Maksimal User
                            <span class="text-slate-400 font-normal">(Opsional)</span></label>
                        <input type="number" name="max_user" min="1" placeholder="Kosongkan jika tak terbatas"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Fitur <span
                                class="text-slate-400 font-normal">(Satu fitur per baris)</span></label>
                        <textarea name="fitur" rows="4" placeholder="Analitik lanjutan&#10;Support prioritas&#10;Tanpa watermark"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3">
                        <button type="button" onclick="document.getElementById('modalTambahPaket').classList.add('hidden')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">
                            Simpan Paket
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Edit Paket --}}
        <div id="modalEditPaket" tabindex="-1" aria-hidden="true"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div
                class="bg-white rounded-3xl shadow-2xl border border-slate-100 p-6 sm:p-8 w-full max-w-md animate-in fade-in zoom-in duration-200">
                <div class="flex justify-between items-center mb-5 pb-3 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-900">Edit Paket Langganan</h3>
                    <button type="button" onclick="document.getElementById('modalEditPaket').classList.add('hidden')"
                        class="w-8 h-8 rounded-full bg-slate-100 text-slate-400 hover:text-rose-600 flex items-center justify-center font-bold transition">&times;</button>
                </div>

                <form id="formEditPaket" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Nama
                            Paket</label>
                        <input type="text" name="nama_paket" id="edit_nama_paket" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Harga per
                            Bulan (Rp)</label>
                        <input type="number" name="harga" id="edit_harga" required min="0"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Maksimal
                            User</label>
                        <input type="number" name="max_user" id="edit_max_user" min="1"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-500 mb-1.5">Fitur <span
                                class="text-slate-400 font-normal">(Satu fitur per baris)</span></label>
                        <textarea name="fitur" id="edit_fitur" rows="4"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3.5 text-sm font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 transition"></textarea>
                    </div>
                    <div class="flex justify-end gap-2.5 pt-3">
                        <button type="button" onclick="document.getElementById('modalEditPaket').classList.add('hidden')"
                            class="px-4 py-2.5 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50 transition">Batal</button>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-xl text-xs shadow-md shadow-blue-500/20 transition-all">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(plan) {
            document.getElementById('formEditPaket').action = '/developer/paket/' + plan.id;
            document.getElementById('edit_nama_paket').value = plan.nama_paket;
            document.getElementById('edit_harga').value = plan.harga;
            document.getElementById('edit_max_user').value = plan.max_user ?? '';
            document.getElementById('edit_fitur').value = (plan.fitur ?? []).join('\n');
            document.getElementById('modalEditPaket').classList.remove('hidden');
        }
    </script>
@endsection
