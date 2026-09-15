@extends('layouts.app')

@section('title', 'Verifikasi QRIS Manual')

@section('container')
    <div class="max-w-7xl mx-auto space-y-4 sm:space-y-6 pb-32 mt-4 sm:mt-7">

        {{-- ========== HEADER ========== --}}
        <div
            class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-800">Verifikasi QRIS Manual</h1>
                    <p class="text-xs sm:text-sm text-slate-500">Verifikasi pembayaran QRIS dari user</p>
                </div>
            </div>
        </div>

        {{-- ========== ALERT ========== --}}
        @if (session('success'))
            <div
                class="flex items-start gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('warning'))
            <div
                class="flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('warning') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div
                class="flex items-start gap-3 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3.5 rounded-xl text-sm font-medium">
                <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ========== SUMMARY CARDS ========== --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                <p class="text-[10px] text-amber-700 font-bold uppercase tracking-wider">Menunggu Verifikasi</p>
                <p class="text-2xl font-black text-amber-600 mt-1">{{ $summary['menunggu'] }}</p>
            </div>
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4">
                <p class="text-[10px] text-slate-700 font-bold uppercase tracking-wider">Pending</p>
                <p class="text-2xl font-black text-slate-600 mt-1">{{ $summary['pending'] }}</p>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                <p class="text-[10px] text-emerald-700 font-bold uppercase tracking-wider">Approved Hari Ini</p>
                <p class="text-2xl font-black text-emerald-600 mt-1">{{ $summary['approved_hari_ini'] }}</p>
            </div>
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                <p class="text-[10px] text-blue-700 font-bold uppercase tracking-wider">Total Hari Ini</p>
                <p class="text-lg font-black text-blue-600 mt-1">Rp
                    {{ number_format($summary['total_hari_ini'], 0, ',', '.') }}</p>
            </div>
        </div>

        {{-- ========== FILTER ========== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-3">
                <select name="status"
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/30 focus:bg-white">
                    <option value="">Semua Status</option>
                    <option value="menunggu_verifikasi" {{ request('status') == 'menunggu_verifikasi' ? 'selected' : '' }}>
                        Menunggu Verifikasi</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>

                <button type="submit"
                    class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-xl text-sm font-bold transition-all active:scale-95">
                    Filter
                </button>

                @if (request('status'))
                    <a href="{{ route('developer.qris.index') }}"
                        class="bg-white hover:bg-slate-50 border border-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-bold transition-all text-center">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        {{-- ========== LIST ========== --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-slate-50 text-[11px] font-bold uppercase text-slate-500">
                        <tr>
                            <th class="px-4 py-3 text-left">Kode</th>
                            <th class="px-4 py-3 text-left">Toko</th>
                            <th class="px-4 py-3 text-left">Paket</th>
                            <th class="px-4 py-3 text-right">Jumlah</th>
                            <th class="px-4 py-3 text-center">Bukti</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($pembayarans as $p)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-4 py-3">
                                    <p class="font-mono text-xs font-bold text-slate-700">{{ $p->order_id }}</p>
                                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $p->created_at->format('d M Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-bold text-slate-800">{{ $p->tenant->nama_toko ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $p->tenant->no_hp ?? '-' }}</p>
                                </td>
                                <td class="px-4 py-3 text-slate-600">{{ $p->plan->nama_paket ?? '-' }}</td>
                                <td class="px-4 py-3 text-right font-bold text-emerald-600">
                                    Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($p->bukti_pembayaran)
                                        <a href="{{ Storage::url($p->bukti_pembayaran) }}" target="_blank"
                                            class="inline-flex items-center gap-1 text-xs text-blue-600 hover:text-blue-800 font-bold">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span
                                        class="text-[10px] bg-{{ $p->status_color }}-100 text-{{ $p->status_color }}-700 px-2 py-1 rounded font-bold uppercase">
                                        {{ $p->status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex justify-center gap-1.5">
                                        @if ($p->status === 'menunggu_verifikasi')
                                            <form action="{{ route('developer.qris.approve', $p->id) }}" method="POST"
                                                onsubmit="return handleApprove(this)">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">
                                                    ✓ Approve
                                                </button>
                                            </form>
                                            <button onclick="openReject({{ $p->id }})"
                                                class="px-3 py-1.5 bg-rose-50 text-rose-600 border border-rose-200 rounded-lg text-xs font-bold hover:bg-rose-100 transition">
                                                ✕ Reject
                                            </button>
                                        @else
                                            <a href="{{ route('developer.qris.show', $p->id) }}"
                                                class="px-3 py-1.5 bg-slate-50 text-slate-600 border border-slate-200 rounded-lg text-xs font-bold hover:bg-slate-100 transition">
                                                Detail
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-16 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 mb-3 text-slate-300" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <p class="font-medium text-slate-500">Belum ada pembayaran QRIS</p>
                                        <p class="text-xs text-slate-400 mt-1">Pembayaran akan muncul di sini saat user
                                            upload bukti</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($pembayarans->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $pembayarans->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ========== MODAL REJECT ========== --}}
    <div id="reject-modal"
        class="hidden fixed inset-0 z-[60] bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
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
                <textarea name="catatan" required placeholder="Contoh: Bukti transfer tidak jelas / jumlah tidak sesuai"
                    rows="4"
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

        // Close modal on backdrop click
        document.addEventListener('click', (e) => {
            if (e.target.id === 'reject-modal') {
                closeReject();
            }
        });
    </script>
@endsection
