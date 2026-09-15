<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran QRIS - {{ $plan->nama_paket }} | Omzetly.id</title>
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body
    class="bg-slate-200 min-h-screen flex items-center justify-center p-4 antialiased selection:bg-emerald-600 selection:text-white">

    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl border border-slate-200 p-6 sm:p-8">

        {{-- Header --}}
        <div class="text-center mb-6">
            <div
                class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-50 border border-emerald-100 mb-4">
                <img class="object-contain w-14 h-14" src="{{ asset('assets/images/logo/favicon.png') }}"
                    alt="logo">
            </div>
            <h1 class="text-xl font-bold text-slate-900">Pembayaran via QRIS</h1>
            <p class="text-xs text-slate-500 mt-1">Scan QRIS dengan aplikasi apa saja</p>
        </div>

        {{-- Alert --}}
        @if (session('error'))
            <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-2xl text-xs font-medium mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-2xl text-xs font-medium mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- Info Toko --}}
        <div class="bg-blue-50/60 border border-blue-100 rounded-2xl p-4 mb-4 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-600 text-white flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            <div class="overflow-hidden">
                <span class="text-[10px] uppercase tracking-wider font-bold text-blue-600 block">Toko</span>
                <span class="font-extrabold text-slate-900 text-sm truncate block">{{ $tenant->nama_toko }}</span>
            </div>
        </div>

        {{-- Info Paket & Harga --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-4 space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500 font-medium">Paket</span>
                <span
                    class="font-bold text-blue-700 bg-blue-100 px-3 py-1 rounded-lg text-xs">{{ $plan->nama_paket }}</span>
            </div>
            <div class="flex justify-between items-center border-t border-slate-200 pt-3">
                <span class="text-slate-500 font-medium text-xs">Total Bayar</span>
                <span class="text-2xl font-extrabold text-blue-600">Rp
                    {{ number_format($plan->harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-500">Kode Pembayaran</span>
                <span class="font-mono font-bold text-slate-700">{{ $pembayaran->order_id }}</span>
            </div>
        </div>

        {{-- QR Code --}}
        <div class="bg-white border-2 border-slate-200 rounded-2xl p-4 mb-4">
            <img src="{{ asset('assets/images/qris-omzetly.png') }}" alt="QRIS Omzetly" class="w-full">
            <p class="text-[10px] text-slate-500 text-center mt-2">
                Scan dengan GoPay, OVO, Dana, ShopeePay, atau m-banking
            </p>
        </div>

        {{-- Instruksi --}}
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-4">
            <p class="text-xs font-bold text-amber-800 mb-2">📋 Cara Bayar:</p>
            <ol class="text-xs text-amber-700 space-y-1 list-decimal list-inside">
                <li>Buka aplikasi e-wallet / m-banking</li>
                <li>Pilih menu "Scan QRIS"</li>
                <li>Scan QR di atas</li>
                <li>Masukkan nominal: <strong>Rp {{ number_format($plan->harga, 0, ',', '.') }}</strong></li>
                <li>Konfirmasi pembayaran</li>
                <li>Screenshot bukti pembayaran</li>
                <li>Upload bukti di bawah ini</li>
            </ol>
        </div>

        {{-- Form Upload Bukti --}}
        @if ($pembayaran->canUploadBukti())
            <form action="{{ route('qris.upload') }}" method="POST" enctype="multipart/form-data" class="mb-4"
                onsubmit="return handleUpload(this)">
                @csrf
                <input type="hidden" name="pembayaran_id" value="{{ $pembayaran->id }}">

                <label class="block text-xs font-bold text-slate-700 mb-2 uppercase tracking-wide">
                    Upload Bukti Transfer <span class="text-rose-500">*</span>
                </label>
                <input type="file" name="bukti" accept="image/*" required
                    class="w-full text-sm border border-slate-200 rounded-xl px-3 py-2.5 mb-3 focus:outline-none focus:ring-2 focus:ring-emerald-500/30 focus:border-emerald-500">

                <button type="submit" id="btnUpload"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl transition-all shadow-lg shadow-emerald-500/20 disabled:opacity-50 flex items-center justify-center gap-2">
                    <svg id="uploadIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                    <svg id="uploadSpinner" class="hidden w-5 h-5 animate-spin" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                    <span id="uploadText">✅ Saya Sudah Bayar</span>
                </button>
            </form>
        @else
            {{-- Status: sudah upload / verified --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-4 text-center">
                <p class="text-sm font-bold text-blue-800">
                    @if ($pembayaran->status === 'menunggu_verifikasi')
                        ⏳ Bukti sudah diupload
                    @elseif ($pembayaran->status === 'confirmed')
                        ✅ Pembayaran sudah dikonfirmasi
                    @else
                        Status: {{ $pembayaran->status_label }}
                    @endif
                </p>
                <p class="text-xs text-blue-600 mt-1">
                    @if ($pembayaran->status === 'menunggu_verifikasi')
                        Mohon tunggu verifikasi admin (maks 1x24 jam)
                    @elseif ($pembayaran->status === 'confirmed')
                        Akun Anda sudah aktif
                    @endif
                </p>
            </div>

            <a href="{{ route('qris.status', $pembayaran->order_id) }}"
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white text-center font-bold py-3.5 rounded-2xl mb-4 transition-all">
                Lihat Status Pembayaran
            </a>
        @endif

        {{-- Tombol Bantuan --}}
        <div class="text-center pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500 mb-2">Ada kendala?</p>
            <a href="https://wa.me/628386606623?text=Halo, saya mau bayar QRIS. Kode: {{ $pembayaran->order_id }}"
                target="_blank"
                class="inline-flex items-center gap-2 text-sm font-bold text-green-600 hover:text-green-700">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Chat Admin via WhatsApp
            </a>
        </div>
    </div>

    <script>
        function handleUpload(form) {
            const btn = document.getElementById('btnUpload');
            const icon = document.getElementById('uploadIcon');
            const spinner = document.getElementById('uploadSpinner');
            const text = document.getElementById('uploadText');

            if (btn.disabled) return false;

            btn.disabled = true;
            icon.classList.add('hidden');
            spinner.classList.remove('hidden');
            text.textContent = 'Mengupload...';

            return true;
        }
    </script>

</body>

</html>
