<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ $plan->nama_paket }} | Omzetly.id</title>
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
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

    <script
        src="{{ config('midtrans.is_production')
            ? 'https://app.midtrans.com/snap/snap.js'
            : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body
    class="bg-slate-200 h-full flex items-center justify-center p-4 antialiased selection:bg-blue-600 selection:text-white">

    <div
        class="max-w-md w-full bg-white rounded-3xl shadow-2xl shadow-slate-300/70 border border-slate-200 p-8 sm:p-10">

        {{-- Logo & Header --}}
        <div class="text-center mb-6">
            <div
                class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-blue-50 border border-blue-100 mb-4 shadow-sm">
                <img src="{{ asset('assets/images/omzetly.png') }}" alt="Omzetly" class="h-12 w-12 object-contain">
            </div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Selesaikan Pembayaran</h1>
            <p class="text-xs text-slate-500 mt-1">Lanjutkan transaksi untuk mengaktifkan langganan Anda.</p>
        </div>

        <div>
            {{-- Info Toko / Tenant (Dipertegas dengan Ikon & Box Khusus) --}}
            <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 mb-4 flex items-center gap-3.5 shadow-sm">
                <div
                    class="w-11 h-11 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0 shadow-md shadow-blue-500/20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="overflow-hidden">
                    <span class="text-[10px] uppercase tracking-wider font-bold text-blue-600 block">Toko</span>
                    <span
                        class="font-extrabold text-slate-900 text-base tracking-tight truncate block">{{ $tenant->nama_toko }}</span>
                </div>
            </div>

            <!-- Info Paket & Total Harga (Nama Paket Diperbesar & Dipertegas) -->
            <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 mb-8 space-y-4 shadow-sm">
                <div class="flex justify-between items-center">
                    <span class="text-slate-500 font-medium text-xs uppercase tracking-wider">Paket Langganan</span>
                    <span
                        class="font-extrabold text-slate-900 text-base bg-blue-100/60 text-blue-800 px-3.5 py-1 rounded-xl border border-blue-200/60 shadow-sm">
                        {{ $plan->nama_paket }}
                    </span>
                </div>
                <div class="flex justify-between items-center border-t border-slate-200 pt-3">
                    <span class="text-slate-500 font-medium text-xs uppercase tracking-wider">Total Bayar</span>
                    <span class="text-2xl font-extrabold text-blue-600">
                        Rp {{ number_format($plan->harga, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <!-- Tombol Bayar -->
            <button onclick="payNow()" id="pay-button"
                class="w-full bg-blue-600 hover:bg-blue-700 transition-all hover:-translate-y-0.5 py-4 rounded-2xl text-white font-semibold text-sm shadow-xl shadow-blue-500/25 flex items-center justify-center gap-2">
                <span>Bayar Sekarang</span>
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7" />
                </svg>
            </button>

            <p class="text-center text-xs text-slate-400 mt-6 font-medium">
                Pembayaran terenkripsi & aman oleh <strong class="text-slate-600">Midtrans</strong>
            </p>
        </div>
    </div>

    <script>
        function payNow() {
            const button = document.getElementById('pay-button');
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                Memproses Pembayaran...
            `;

            snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    window.location.href = "{{ route('payment.finish') }}";
                },
                onPending: function(result) {
                    alert('Pembayaran Anda sedang diproses. Kami akan mengupdate status secara otomatis.');
                    window.location.href = "{{ route('status.langganan') ?? '/' }}";
                },
                onError: function(result) {
                    alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'));
                    button.disabled = false;
                    button.innerHTML = `
                        <span>Bayar Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7"/></svg>
                    `;
                },
                onClose: function() {
                    button.disabled = false;
                    button.innerHTML = `
                        <span>Bayar Sekarang</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7-7 7"/></svg>
                    `;
                }
            });
        }
    </script>

</body>

</html>
