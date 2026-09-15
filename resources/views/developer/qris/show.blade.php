@extends('layouts.app')

@section('title', 'Detail Pembayaran QRIS')

@section('container')
<div class="max-w-4xl mx-auto space-y-4 pb-32 mt-4 sm:mt-7">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('developer.qris.index') }}"
                class="w-10 h-10 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-lg font-extrabold text-slate-800">Detail Pembayaran QRIS</h1>
                <p class="text-xs text-slate-500 font-mono">{{ $pembayaran->order_id }}</p>
            </div>
        </div>

        <span class="text-[10px] bg-{{ $pembayaran->status_color }}-100 text-{{ $pembayaran->status_color }}-700 px-3 py-1.5 rounded-lg font-bold uppercase">
            {{ $pembayaran->status_label }}
        </span>
    </div>

    {{-- Detail Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        {{-- Info Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Info Pembayaran</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Order ID</span>
                    <span class="font-mono font-bold text-slate-700 text-xs">{{ $pembayaran->order_id }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Paket</span>
                    <span class="font-bold text-slate-700">{{ $pembayaran->plan->nama_paket ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Jumlah</span>
                    <span class="font-bold text-emerald-600">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Metode</span>
                    <span class="font-bold text-slate-700">{{ strtoupper($pembayaran->metode) }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-3">
                    <span class="text-slate-500">Dibuat</span>
                    <span class="text-slate-700 text-xs">{{ $pembayaran->created_at->format('d M Y H:i') }}</span>
                </div>
                @if($pembayaran->tanggal_konfirmasi)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Dikonfirmasi</span>
                        <span class="text-slate-700 text-xs">{{ $pembayaran->tanggal_konfirmasi->format('d M Y H:i') }}</span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Info Tenant --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Info Toko</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-slate-500">Nama Toko</span>
                    <span class="font-bold text-slate-700">{{ $pembayaran->tenant->nama_toko ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Pemilik</span>
                    <span class="text-slate-700">{{ $pembayaran->tenant->nama_pemilik ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">No HP</span>
                    <span class="text-slate-700">{{ $pembayaran->tenant->no_hp ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Email</span>
                    <span class="text-slate-700 text-xs">{{ $pembayaran->tenant->email ?? '-' }}</span>
                </div>
                <div class="flex justify-between border-t border-slate-100 pt-3">
                    <span class="text-slate-500">Status Langganan</span>
                    <span class="font-bold text-slate-700 uppercase text-xs">{{ $pembayaran->tenant->status_langganan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Bukti Transfer --}}
    @if($pembayaran->bukti_pembayaran)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Bukti Transfer</h3>
            <div class="bg-slate-50 rounded-xl p-4 flex justify-center">
                <img src="{{ Storage::url($pembayaran->bukti_pembayaran) }}"
                    alt="Bukti Transfer"
                    class="max-w-full max-h-96 rounded-xl border border-slate-200 shadow-sm">
            </div>
            <div class="flex justify-center mt-3">
                <a href="{{ Storage::url($pembayaran->bukti_pembayaran) }}" target="_blank"
                    class="text-sm text-blue-600 hover:underline font-bold">
                    Lihat Full Size →
                </a>
            </div>
        </div>
    @endif

    {{-- Catatan Admin --}}
    @if($pembayaran->catatan_admin || $pembayaran->verified_at)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Verifikasi Admin</h3>
            <div class="space-y-2 text-sm">
                @if($pembayaran->verified_at)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Diverifikasi</span>
                        <span class="text-slate-700">{{ $pembayaran->verified_at->format('d M Y H:i') }}</span>
                    </div>
                @endif
                @if($pembayaran->verifier)
                    <div class="flex justify-between">
                        <span class="text-slate-500">Oleh</span>
                        <span class="text-slate-700">{{ $pembayaran->verifier->name }}</span>
                    </div>
                @endif
                @if($pembayaran->catatan_admin)
                    <div class="pt-2">
                        <p class="text-slate-500 mb-1">Catatan:</p>
                        <p class="text-slate-700 bg-slate-50 rounded-lg px-3 py-2 text-xs">{{ $pembayaran->catatan_admin }}</p>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- Aksi --}}
    @if($pembayaran->status === 'menunggu_verifikasi')
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-5">
            <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-4">Aksi</h3>
            <div class="flex gap-3">
                <form action="{{ route('developer.qris.approve', $pembayaran->id) }}" method="POST" class="flex-1" onsubmit="return handleApprove(this)">
                    @csrf
                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        Approve Pembayaran
                    </button>
                </form>

                <button onclick="openReject({{ $pembayaran->id }})"
                    class="flex-1 bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 font-bold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Reject
                </button>
            </div>
        </div>
    @endif
</div>

{{-- ========== MODAL REJECT ========== --}}
<div id="reject-modal" class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h3 class="font-bold text-slate-800">Tolak Pembayaran</h3>
        </div>
        <form id="reject-form" method="POST">
            @csrf
            <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">
                Alasan Penolakan <span class="text-rose-500">*</span>
            </label>
            <textarea name="catatan" required placeholder="Contoh: Bukti transfer tidak jelas / jumlah tidak sesuai" rows="4"
                class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm mb-4 focus:ring-2 focus:ring-rose-500/30 focus:border-rose-500"></textarea>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeReject()"
                    class="px-4 py-2 border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-sm font-bold">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function openReject(id) {
        document.getElementById('reject-form').action = '{{ url('/developer/qris-verification') }}/' + id + '/reject';
        document.getElementById('reject-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeReject() {
        document.getElementById('reject-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function handleApprove(form) {
        if (!confirm('Approve pembayaran ini? Akun user akan otomatis aktif.')) {
            return false;
        }

        const btn = form.querySelector('button');
        btn.disabled = true;
        btn.innerHTML = 'Memproses...';

        return true;
    }
</script>
@endsection