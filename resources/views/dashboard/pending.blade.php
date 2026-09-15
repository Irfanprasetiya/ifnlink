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

        // ✅ Cek status QRIS
        $hasQrisWaiting = isset($pendingQris) && $pendingQris && $pendingQris->status === 'menunggu_verifikasi';
        $hasQrisRejected = isset($pendingQris) && $pendingQris && $pendingQris->status === 'rejected';
    @endphp

    {{-- Penyesuaian py-10 untuk mobile, py-16 untuk layar lebih besar --}}
    <div class="w-full max-w-md mx-auto py-10 sm:py-16 px-4 sm:px-6 text-center flex flex-col justify-center min-h-[80vh]">
        {{-- Penyesuaian padding card p-6 untuk mobile, p-10 untuk desktop --}}
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 p-6 sm:p-10 transition-all">

            {{-- ============================================ --}}
            {{-- SKENARIO 1: SUDAH UPLOAD BUKTI → DIVERIFIKASI --}}
            {{-- ============================================ --}}
            @if ($hasQrisWaiting)
                <div
                    class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-blue-50 border border-blue-100 text-blue-600 mb-5 sm:mb-6 shadow-sm">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mb-2">
                    Pembayaran Sedang Diverifikasi
                </h1>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Terima kasih! Bukti pembayaran Anda untuk paket
                    <strong class="text-slate-800">{{ $tenant->plan->nama_paket ?? 'PRO' }}</strong>
                    sudah kami terima. Admin akan memverifikasi maksimal <strong>1x24 jam</strong>.
                </p>

                {{-- Info box: penyesuaian ukuran text agar nominal lebih terbaca --}}
                <div class="bg-blue-50/50 border border-blue-100 rounded-2xl p-4 sm:p-5 mb-6 text-left space-y-2">
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-blue-600/80">Kode</span>
                        <span class="font-mono font-bold text-blue-900">{{ $pendingQris->order_id }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="text-blue-600/80">Jumlah</span>
                        <span class="font-bold text-blue-900">Rp
                            {{ number_format($pendingQris->jumlah, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between items-center text-xs sm:text-sm">
                        <span class="text-blue-600/80">Diupload</span>
                        <span class="text-blue-800">{{ $pendingQris->updated_at->format('d M Y H:i') }}</span>
                    </div>
                </div>

                <a href="{{ route('qris.status', $pendingQris->order_id) }}"
                    class="w-full inline-flex items-center justify-center gap-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 text-sm sm:text-base mb-3.5">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <span>Lihat Status Pembayaran</span>
                </a>

                @php
                    $waMessage =
                        "Halo Admin Omzetly,\n\n" .
                        "Saya sudah upload bukti pembayaran QRIS.\n\n" .
                        "Detail:\n" .
                        "• Kode: {$pendingQris->order_id}\n" .
                        '• Toko: ' .
                        ($tenant->nama_toko ?? '-') .
                        "\n" .
                        '• Paket: ' .
                        ($tenant->plan->nama_paket ?? '-') .
                        "\n" .
                        '• Jumlah: Rp ' .
                        number_format($pendingQris->jumlah, 0, ',', '.') .
                        "\n\n" .
                        'Mohon diverifikasi. Terima kasih.';
                @endphp

                <a href="https://wa.me/628386606623?text={{ urlencode($waMessage) }}" target="_blank"
                    class="w-full inline-flex items-center justify-center gap-2.5 bg-[#25D366] hover:bg-[#20bd5a] text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-[#25D366]/20 transition-all hover:-translate-y-0.5 text-sm sm:text-base mb-4">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    <span>Chat Admin via WhatsApp</span>
                </a>

                <p class="text-xs text-slate-400 mt-2">
                    Halaman akan otomatis refresh saat admin menyetujui pembayaran
                </p>

                {{-- ============================================ --}}
                {{-- SKENARIO 2: BUKTI DITOLAK                    --}}
                {{-- ============================================ --}}
            @elseif ($hasQrisRejected)
                <div
                    class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 mb-5 sm:mb-6 shadow-sm">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mb-2">
                    Bukti Pembayaran Ditolak
                </h1>
                <p class="text-sm text-slate-500 mb-6 leading-relaxed">
                    Bukti pembayaran Anda ditolak oleh admin. Silakan upload bukti baru.
                </p>

                @if ($pendingQris->catatan_admin)
                    <div class="bg-rose-50/80 border border-rose-200 rounded-2xl p-4 sm:p-5 mb-6 text-left">
                        <p class="text-xs sm:text-sm font-bold text-rose-800 mb-1">Alasan Penolakan:</p>
                        <p class="text-sm text-rose-600 leading-relaxed">{{ $pendingQris->catatan_admin }}</p>
                    </div>
                @endif

                <a href="{{ route('qris.show', ['plan_id' => $tenant->plan_id]) }}"
                    class="w-full inline-flex items-center justify-center gap-2.5 bg-rose-600 hover:bg-rose-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-rose-600/20 transition-all hover:-translate-y-0.5 text-sm sm:text-base mb-4">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                    </svg>
                    <span>Upload Bukti Baru</span>
                </a>

                {{-- ============================================ --}}
                {{-- SKENARIO 3: BELUM UPLOAD / DEFAULT           --}}
                {{-- ============================================ --}}
            @else
                <div
                    class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl {{ $config['icon_bg'] }} mb-5 sm:mb-6 shadow-sm border">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $config['icon_path'] }}" />
                    </svg>
                </div>

                <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900 mb-2">{{ $config['title'] }}</h1>
                <p class="text-sm text-slate-500 mb-8 leading-relaxed">
                    {!! $config['message'] !!}
                </p>

                @if ($config['button_label'])
                    <a href="{{ $config['button_route'] }}"
                        class="w-full inline-flex items-center justify-center gap-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-0.5 text-sm sm:text-base mb-4">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>{{ $config['button_label'] }}</span>
                    </a>

                    @if ($tenant->status_langganan === 'pending' && $tenant->plan_id)
                        {{-- Menggunakan flex divider untuk desain responsif yang lebih solid --}}
                        <div class="flex items-center my-6">
                            <div class="flex-grow border-t border-slate-200"></div>
                            <span class="px-4 text-xs text-slate-400 font-medium bg-white">atau</span>
                            <div class="flex-grow border-t border-slate-200"></div>
                        </div>

                        <a href="{{ route('qris.show', ['plan_id' => $tenant->plan_id]) }}"
                            class="w-full inline-flex items-center justify-center gap-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-6 rounded-xl shadow-lg shadow-emerald-600/20 transition-all hover:-translate-y-0.5 text-sm sm:text-base mb-4">
                            <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm14 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                            <span>Bayar via QRIS</span>
                        </a>
                    @endif
                @endif
            @endif

            {{-- Link Status Langganan --}}
            <div class="mt-4 pt-4 border-t border-slate-100">
                <a href="{{ route('status.langganan') }}"
                    class="inline-flex items-center gap-1.5 text-sm font-semibold text-slate-500 hover:text-blue-600 transition-colors">
                    <span>Lihat Riwayat Langganan</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>

    @if ($hasQrisWaiting)
        <script>
            // Auto-check status setiap 30 detik
            setInterval(async () => {
                try {
                    const res = await fetch('{{ route('qris.check', $pendingQris->order_id) }}');
                    const data = await res.json();

                    if (data.status !== 'menunggu_verifikasi') {
                        window.location.reload();
                    }
                } catch (e) {
                    console.error('Check error:', e);
                }
            }, 30000);
        </script>
    @endif
@endsection
