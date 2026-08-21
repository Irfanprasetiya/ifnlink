<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout — Omzetly.id</title>
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'ui-sans-serif', 'system-ui']
                    }
                }
            }
        }
    </script>
    {{-- Snap JS hanya dimuat jika ada token --}}
    @if (isset($existingToken) && $existingToken)
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}">
        </script>
    @endif
</head>

<body
    class="bg-slate-200 h-full flex items-center justify-center p-4 antialiased selection:bg-blue-600 selection:text-white">
    <div
        class="bg-white rounded-3xl shadow-2xl shadow-slate-300/70 border border-slate-200 max-w-md w-full p-8 sm:p-10">

        {{-- Logo & Header --}}
        <div class="text-center mb-8">
            <div
                class="inline-flex items-center justify-center w-24 h-24 rounded-2xl bg-blue-50 border border-blue-100 mb-4 shadow-sm">
                <img class="object-contain" src="{{ asset('assets/images/logo/favicon.png') }}" alt="logo">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Konfirmasi Checkout</h1>
            <p class="text-xs text-slate-500 mt-1">Selesaikan langganan untuk mengaktifkan fitur penuh.</p>
        </div>

        {{-- Session Notifications --}}
        @if (session('error'))
            <div
                class="flex items-center gap-2.5 bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-2xl text-xs font-medium mb-6">
                <svg class="w-4 h-4 shrink-0 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div
                class="flex items-center gap-2.5 bg-blue-50 border border-blue-100 text-blue-700 px-4 py-3 rounded-2xl text-xs font-medium mb-6">
                <svg class="w-4 h-4 shrink-0 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        {{-- Detail Ringkasan Paket --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-6 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500 font-medium">Paket Langganan</span>
                <span
                    class="font-bold text-slate-900 bg-blue-50 text-blue-700 px-3 py-1 rounded-full text-xs border border-blue-100">{{ $plan->nama_paket }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500 font-medium">Total Harga</span>
                <span class="font-extrabold text-slate-900 text-base">Rp
                    {{ number_format($plan->harga, 0, ',', '.') }}</span>
            </div>
            @if ($tenant)
                <div class="flex justify-between items-center text-sm border-t border-slate-200 pt-3 mt-1">
                    <span class="text-slate-500 font-medium">Nama Toko</span>
                    <span class="font-semibold text-slate-800 text-xs">{{ $tenant->nama_toko }}</span>
                </div>
            @endif
        </div>

        {{-- Kontrol Aksi Pembayaran --}}
        @if (isset($existingToken) && $existingToken)
            <div
                class="flex items-center gap-2.5 bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-2xl text-xs font-medium mb-5">
                <svg class="w-4 h-4 shrink-0 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Anda memiliki sesi pembayaran yang belum selesai.</span>
            </div>

            {{-- Tombol Lanjutkan --}}
            <button onclick="continuePayment()"
                class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-2xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 mb-3 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Lanjutkan Pembayaran Sebelumnya
            </button>

            {{-- Tombol Ganti Metode --}}
            <form action="{{ route('pay') }}" method="POST" class="mb-3">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3.5 px-4 rounded-2xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Ganti Metode Pembayaran
                </button>
            </form>

            <p class="text-[11px] text-slate-400 text-center font-medium">Memilih metode baru akan membatalkan tagihan
                order sebelumnya.</p>
        @else
            {{-- Tombol Bayar Baru --}}
            <form action="{{ route('pay') }}" method="POST">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3.5 px-4 rounded-2xl shadow-lg shadow-blue-500/20 transition-all hover:-translate-y-0.5 text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    Bayar Sekarang
                </button>
            </form>
        @endif

        {{-- Kembali --}}
        <div class="text-center mt-6">
            @auth
                <a href="{{ route('status.langganan') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Status Langganan
                </a>
            @else
                <a href="{{ url('/') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            @endauth
        </div>
    </div>

    @if (isset($existingToken) && $existingToken)
        <script>
            function continuePayment() {
                window.snap.pay('{{ $existingToken }}', {
                    onSuccess: function(result) {
                        // ✅ Kirim data lengkap
                        let params = new URLSearchParams({
                            order_id: result.order_id || '{{ $orderId }}',
                            transaction_id: result.transaction_id || result.order_id ||
                                '{{ $orderId }}',
                            transaction_status: result.transaction_status || 'settlement',
                            payment_type: result.payment_type || '',
                        });

                        window.location.href = '{{ route('payment.finish') }}?' + params.toString();
                    },
                    onPending: function(result) {
                        alert('Pembayaran pending, silakan selesaikan.');
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        alert('Anda menutup popup. Pembayaran bisa dilanjutkan nanti.');
                    }
                });
            }
        </script>
    @endif
</body>

</html>
