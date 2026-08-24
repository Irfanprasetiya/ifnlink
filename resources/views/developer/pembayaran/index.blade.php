@extends('layouts.app')

@section('title', 'Riwayat Pembayaran')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Header --}}
        <div
            class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
            <div>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Riwayat Pembayaran</h1>
                <p class="text-sm text-slate-500 mt-1">Semua transaksi pembayaran tenant melalui Midtrans.</p>
            </div>
            <div class="flex gap-2 flex-wrap">
                <span
                    class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-full font-bold">{{ $stats['success'] }}
                    Lunas</span>
                <span class="text-xs bg-amber-50 text-amber-700 px-3 py-1.5 rounded-full font-bold">{{ $stats['pending'] }}
                    Pending</span>
                <span class="text-xs bg-rose-50 text-rose-700 px-3 py-1.5 rounded-full font-bold">{{ $stats['failed'] }}
                    Gagal</span>
                <span
                    class="text-xs bg-slate-100 text-slate-600 px-3 py-1.5 rounded-full font-bold">{{ $stats['cancelled'] }}
                    Dibatalkan</span>
            </div>
        </div>

        {{-- Filter --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <form method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama toko atau pemilik..."
                        class="w-full md:w-64 bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 text-sm">
                    <select name="status" class="bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 text-sm">
                        <option value="semua" {{ !request('status') || request('status') == 'semua' ? 'selected' : '' }}>
                            Semua
                            Status</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Lunas</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                        </option>
                    </select>

                    {{-- ✅ Filter Range Tanggal --}}
                    <div class="flex items-center gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 text-sm">
                        <span class="text-slate-500 text-sm font-medium">s/d</span>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="bg-slate-50 border border-slate-300 rounded-lg px-4 py-2 text-sm">
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-bold w-full md:w-auto">
                        Filter
                    </button>
                    <a href="{{ route('developer.pembayaran.index') }}"
                        class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-5 py-2 rounded-lg text-sm font-bold text-center w-full md:w-auto">
                        Reset
                    </a>
                    <a href="{{ route('developer.pembayaran.export.pdf') }}?{{ request()->getQueryString() }}"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg text-sm font-bold text-center flex items-center gap-2 ml-auto w-full md:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17v1a2 2 0 002 2h14a2 2 0 002-2v-1M3 7v1a2 2 0 002 2h14a2 2 0 002-2V7M3 7a2 2 0 012-2h14a2 2 0 012 2v0" />
                        </svg>
                        Export PDF
                    </a>
                </div>
            </form>
        </div>

        {{-- Tabel --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-500 uppercase">
                        <tr>
                            <th class="px-6 py-4">#</th>
                            <th class="px-6 py-4">Nama Toko</th>
                            <th class="px-6 py-4 text-right">Nominal</th>
                            <th class="px-6 py-4">Paket</th>
                            <th class="px-6 py-4">Metode</th>
                            <th class="px-6 py-4 text-center">Status</th>
                            <th class="px-6 py-4">Order ID</th>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($pembayarans as $p)
                            <tr class="hover:bg-slate-50">
                                <td class="px-6 py-4 font-bold">
                                    {{ $loop->iteration + ($pembayarans->currentPage() - 1) * $pembayarans->perPage() }}
                                </td>
                                <td class="px-6 py-4 font-bold text-slate-800">
                                    {{ $p->tenant->nama_toko ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right font-bold">
                                    Rp {{ number_format($p->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $p->plan->nama_paket ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $p->metode ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if (in_array($p->status, ['confirmed', 'settlement', 'capture', 'success']))
                                        <span
                                            class="text-[11px] bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-full font-bold">LUNAS</span>
                                    @elseif ($p->status == 'pending')
                                        <span
                                            class="text-[11px] bg-amber-50 text-amber-700 px-2.5 py-1 rounded-full font-bold">PENDING</span>
                                    @elseif ($p->status == 'cancelled')
                                        <span
                                            class="text-[11px] bg-slate-100 text-slate-600 px-2.5 py-1 rounded-full font-bold">DIBATALKAN</span>
                                    @elseif (in_array($p->status, ['failed', 'deny']))
                                        <span
                                            class="text-[11px] bg-rose-50 text-rose-700 px-2.5 py-1 rounded-full font-bold">GAGAL</span>
                                    @elseif ($p->status == 'expired')
                                        <span
                                            class="text-[11px] bg-orange-50 text-orange-700 px-2.5 py-1 rounded-full font-bold">EXPIRED</span>
                                    @else
                                        <span
                                            class="text-[11px] bg-slate-50 text-slate-700 px-2.5 py-1 rounded-full font-bold">
                                            {{ strtoupper($p->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 font-mono text-xs">
                                    {{ $p->order_id ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-slate-600">
                                    {{ $p->created_at->format('d M Y H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <a href="{{ route('developer.pembayaran.show', $p->id) }}"
                                        class="text-blue-600 hover:text-blue-800 font-bold text-xs">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-10 text-slate-400">
                                    Belum ada data pembayaran
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-200">
                {{ $pembayarans->withQueryString()->links() }}
            </div>
        </div>
    </div>
@endsection
