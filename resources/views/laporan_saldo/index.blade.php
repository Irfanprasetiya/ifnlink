@extends('layouts.app')

@section('title', 'Laporan Saldo')

@section('container')
    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Header & Filter Box --}}
        <div
            class="flex flex-col md:flex-row md:items-center md:justify-between gap-5 bg-white p-6 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
            {{-- Ornamen Background Blur --}}
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </span>
                    Laporan Saldo
                </h1>
                <p class="text-sm text-slate-500 mt-2 font-medium">
                    Ringkasan posisi saldo pada setiap cabang per <strong
                        class="text-slate-700">{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                </p>
            </div>

            <div class="relative z-10">
                <form method="GET" action="{{ route('laporan_saldo.index') }}" class="flex items-center gap-2">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <input type="date" name="tanggal" value="{{ $tanggal }}"
                            class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 pl-10 p-2.5 w-full sm:w-auto transition-colors">
                    </div>
                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-lg text-sm shadow-sm transition-all shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                        </svg>
                        Filter
                    </button>
                </form>
            </div>
        </div>

        {{-- Tabel Data Laporan Saldo --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse whitespace-nowrap">
                    <thead class="bg-slate-50 border-b border-slate-200">
                        <tr class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                            <th class="px-5 py-4 text-center w-12 border-r border-slate-100">No</th>
                            <th class="px-5 py-4 border-r border-slate-100">Cabang</th>
                            @foreach ($banks as $bank)
                                <th class="px-5 py-4 text-right">{{ $bank->nama_bank }}</th>
                            @endforeach
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse ($cabangs as $cabang)
                            <tr class="hover:bg-slate-50/70 transition-colors group">
                                <td class="px-5 py-4 text-center text-slate-500 font-medium border-r border-slate-50">
                                    {{ $loop->iteration }}</td>
                                <td class="px-5 py-4 font-semibold text-slate-800 border-r border-slate-50">
                                    {{ $cabang->nama_cabang }}</td>
                                @foreach ($banks as $bank)
                                    <td class="px-5 py-4 text-right font-medium text-slate-700">
                                        Rp {{ number_format($saldo[$cabang->id][$bank->id] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ 2 + $banks->count() }}" class="text-center py-12">
                                    <div class="flex flex-col items-center justify-center text-slate-500">
                                        <svg class="w-12 h-12 text-slate-300 mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                        <p class="text-sm font-medium">Belum ada data saldo yang tercatat pada tanggal
                                            tersebut.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                    {{-- Footer / Total --}}
                    @if ($cabangs->isNotEmpty())
                        <tfoot class="bg-blue-50/30 border-t-2 border-slate-200 font-bold text-slate-800 text-sm">
                            <tr>
                                <td colspan="2"
                                    class="px-5 py-4 text-right uppercase tracking-wider text-xs text-slate-500 border-r border-slate-200/60">
                                    Total Keseluruhan
                                </td>
                                @foreach ($banks as $bank)
                                    <td class="px-5 py-4 text-right text-blue-700">
                                        Rp {{ number_format($totalSaldo[$bank->id] ?? 0, 0, ',', '.') }}
                                    </td>
                                @endforeach
                            </tr>
                        </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
@endsection
