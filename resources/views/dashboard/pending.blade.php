@extends('layouts.app')

@section('title', 'Dashboard')

@section('container')
    @php
        $config = match ($tenant->status_langganan) {
            'expired' => [
                'icon_bg' => 'bg-rose-50 border-rose-100 text-rose-600',
                'title' => 'Langganan Telah Berakhir',
                'message' =>
                    'Masa aktif paket <strong class="text-slate-800 font-semibold">' .
                    ($tenant->plan->nama_paket ?? 'PRO') .
                    '</strong> Anda sudah habis. Perpanjang sekarang untuk melanjutkan menggunakan semua fitur.',
                'button_label' => 'Perpanjang Sekarang',
                'button_route' => route('status.perpanjang'),
                'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
            'suspended' => [
                'icon_bg' => 'bg-rose-50 border-rose-100 text-rose-600',
                'title' => 'Akun Dinonaktifkan Sementara',
                'message' =>
                    'Akun Anda saat ini <strong class="text-slate-800 font-semibold">dinonaktifkan</strong> oleh admin. Silakan hubungi tim support kami untuk informasi lebih lanjut.',
                'button_label' => null,
                'button_route' => null,
                'icon_path' => 'M18.364 5.636l-12.728 12.728M12 21a9 9 0 100-18 9 9 0 000 18z',
            ],
            default => [
                'icon_bg' => 'bg-amber-50 border-amber-100 text-amber-600',
                'title' => 'Menunggu Pembayaran',
                'message' =>
                    'Langganan <strong class="text-slate-800 font-semibold">' .
                    ($tenant->plan->nama_paket ?? 'PRO') .
                    '</strong> Anda belum aktif. Silakan selesaikan pembayaran untuk mulai menggunakan semua fitur.',
                'button_label' => 'Bayar Sekarang',
                'button_route' => route('checkout', $tenant->plan_id),
                'icon_path' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
            ],
        };
    @endphp

    <div class="max-w-md mx-auto py-16 px-4 text-center">
        <div class="bg-white rounded-3xl shadow-2xl shadow-slate-300/70 border border-slate-200/80 p-8 sm:p-10">

            {{-- Icon Badge --}}
            <div
                class="inline-flex items-center justify-center w-16 h-16 rounded-2xl {{ $config['icon_bg'] }} mb-6 shadow-sm border">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon_path'] }}" />
                </svg>
            </div>

            <h1 class="text-2xl font-bold tracking-tight text-slate-900 mb-2">{{ $config['title'] }}</h1>
            <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                {!! $config['message'] !!}
            </p>

            @if ($config['button_label'])
                <a href="{{ $config['button_route'] }}"
                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded-2xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 text-sm mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <span>{{ $config['button_label'] }}</span>
                </a>
            @endif

            <div>
                <a href="{{ route('status.langganan') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-blue-600 hover:text-blue-700 transition">
                    <span>Lihat Status Langganan</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection
