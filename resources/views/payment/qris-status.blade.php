<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - {{ $pembayaran->order_id }} | Omzetly.id</title>
    <link rel="icon" href="{{ asset('assets/images/omzetly.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-200 min-h-screen flex items-center justify-center p-4 antialiased">

    @php
        $statusConfig = [
            'pending' => [
                'icon' => '⏳',
                'color' => 'amber',
                'title' => 'Menunggu Pembayaran',
                'desc' => 'Silakan selesaikan pembayaran Anda.',
            ],
            'menunggu_verifikasi' => [
                'icon' => '🔍',
                'color' => 'blue',
                'title' => 'Sedang Diverifikasi',
                'desc' => 'Bukti transfer Anda sedang diperiksa admin (maks 1x24 jam).',
            ],
            'confirmed' => [
                'icon' => '✅',
                'color' => 'emerald',
                'title' => 'Pembayaran Berhasil',
                'desc' => 'Akun Anda sudah aktif. Selamat menggunakan!',
            ],
            'rejected' => [
                'icon' => '❌',
                'color' => 'rose',
                'title' => 'Pembayaran Ditolak',
                'desc' => 'Silakan hubungi admin untuk informasi lebih lanjut.',
            ],
            'expired' => [
                'icon' => '⏰',
                'color' => 'slate',
                'title' => 'Pembayaran Kadaluarsa',
                'desc' => 'Silakan buat pembayaran baru.',
            ],
            'cancelled' => [
                'icon' => '🚫',
                'color' => 'slate',
                'title' => 'Pembayaran Dibatalkan',
                'desc' => 'Pembayaran telah dibatalkan.',
            ],
        ];
        $config = $statusConfig[$pembayaran->status] ?? $statusConfig['pending'];

        // ✅ WA Message — hanya pakai $pembayaran (tidak butuh $tenant/$plan)
        $waMessage =
            "Halo Admin Omzetly,\n\n" .
            "Saya sudah upload bukti pembayaran QRIS.\n\n" .
            "📋 Detail Pembayaran:\n" .
            "• Kode: {$pembayaran->order_id}\n" .
            '• Nama Toko: ' .
            ($pembayaran->tenant->nama_toko ?? '-') .
            "\n" .
            '• Pemilik: ' .
            ($pembayaran->tenant->nama_pemilik ?? '-') .
            "\n" .
            '• Email: ' .
            ($pembayaran->tenant->email ?? '-') .
            "\n" .
            '• Paket: ' .
            ($pembayaran->plan->nama_paket ?? '-') .
            "\n" .
            '• Jumlah: Rp ' .
            number_format($pembayaran->jumlah, 0, ',', '.') .
            "\n\n" .
            'Mohon diverifikasi. Terima kasih. 🙏';
    @endphp

    <div class="max-w-md w-full bg-white rounded-3xl shadow-2xl border border-slate-200 p-6 sm:p-8 text-center">

        {{-- Icon Status --}}
        <div
            class="w-20 h-20 mx-auto rounded-full bg-{{ $config['color'] }}-100 flex items-center justify-center text-4xl mb-4">
            {{ $config['icon'] }}
        </div>

        {{-- Title --}}
        <h1 class="text-xl font-bold text-{{ $config['color'] }}-700 mb-2">
            {{ $config['title'] }}
        </h1>
        <p class="text-sm text-slate-500 mb-6">{{ $config['desc'] }}</p>

        {{-- ============================================ --}}
        {{-- KOTAK HIJAU — Hanya untuk menunggu_verifikasi --}}
        {{-- ============================================ --}}
        @if ($pembayaran->status === 'menunggu_verifikasi')
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-2xl p-4 mb-6">
                <p class="text-xs text-green-800 font-bold mb-2">💡 Ingin dipercepat?</p>
                <p class="text-xs text-green-700 mb-4">
                    Kirim pesan ke admin untuk verifikasi lebih cepat
                </p>

                <button type="button" onclick="shareToWhatsApp(this)" id="btnWaTop"
                    class="w-full inline-flex items-center justify-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-green-500/20 active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                    </svg>
                    <span class="btn-text">Salin Pesan & Buka WhatsApp</span>
                </button>

                <p class="text-[10px] text-green-600 mt-2">
                    Pesan akan otomatis ter-copy, tinggal tempel di chat admin
                </p>
            </div>
        @endif

        {{-- ============================================ --}}
        {{-- DETAIL PEMBAYARAN                            --}}
        {{-- ============================================ --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 mb-6 text-left space-y-3">
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500">Kode</span>
                <span class="font-mono font-bold text-slate-700 text-xs">{{ $pembayaran->order_id }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500">Paket</span>
                <span class="font-bold text-slate-700">{{ $pembayaran->plan->nama_paket ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center text-sm">
                <span class="text-slate-500">Toko</span>
                <span class="font-bold text-slate-700">{{ $pembayaran->tenant->nama_toko ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center border-t border-slate-200 pt-3">
                <span class="text-slate-500 text-xs">Jumlah</span>
                <span class="font-extrabold text-blue-600">Rp
                    {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</span>
            </div>

            @if ($pembayaran->bukti_pembayaran)
                <div class="border-t border-slate-200 pt-3">
                    <p class="text-xs text-slate-500 mb-2">Bukti Transfer:</p>
                    <img src="{{ Storage::url($pembayaran->bukti_pembayaran) }}" alt="Bukti Transfer"
                        class="w-full rounded-xl border border-slate-200">
                </div>
            @endif

            @if ($pembayaran->catatan_admin && $pembayaran->status === 'rejected')
                <div class="border-t border-slate-200 pt-3">
                    <p class="text-xs font-bold text-rose-600">Catatan Admin:</p>
                    <p class="text-xs text-rose-500 mt-1">{{ $pembayaran->catatan_admin }}</p>
                </div>
            @endif

            @if ($pembayaran->verified_at)
                <div class="border-t border-slate-200 pt-3">
                    <p class="text-xs text-slate-500">Diverifikasi:</p>
                    <p class="text-xs text-slate-700 font-medium">
                        {{ $pembayaran->verified_at->format('d M Y H:i') }}
                        @if ($pembayaran->verifier)
                            oleh {{ $pembayaran->verifier->name }}
                        @endif
                    </p>
                </div>
            @endif
        </div>

        {{-- ============================================ --}}
        {{-- TOMBOL AKSI                                  --}}
        {{-- ============================================ --}}
        @if ($pembayaran->status === 'pending')
            <a href="{{ route('qris.show', ['plan_id' => $pembayaran->plan_id]) }}"
                class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-2xl mb-3 transition-all">
                Lanjutkan Pembayaran
            </a>
        @endif

        @if ($pembayaran->status === 'rejected')
            <a href="{{ route('qris.show', ['plan_id' => $pembayaran->plan_id]) }}"
                class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl mb-3 transition-all">
                Upload Bukti Baru
            </a>
        @endif

        @if ($pembayaran->status === 'confirmed')
            @auth
                <a href="{{ route('dashboard') }}"
                    class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl mb-3 transition-all">
                    Ke Dashboard
                </a>
            @else
                <a href="{{ route('login') }}"
                    class="block w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3.5 rounded-2xl mb-3 transition-all">
                    Login Sekarang
                </a>
            @endauth
        @endif

        {{-- ============================================ --}}
        {{-- ✅ TOMBOL KEMBALI (LOGIN / STATUS LANGGANAN) --}}
        {{-- ============================================ --}}
        @auth
            {{-- Sudah login → kembali ke status langganan --}}
            <a href="{{ route('status.langganan') }}" onclick="return handleBack(this)"
                class="btn-back block w-full bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-200 font-bold py-3.5 rounded-2xl mb-3 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="btn-content inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Status Langganan
                </span>
            </a>
        @else
            {{-- Belum login → kembali ke login --}}
            <a href="{{ route('login') }}" onclick="return handleBack(this)"
                class="btn-back block w-full bg-white hover:bg-slate-50 text-slate-700 border-2 border-slate-200 font-bold py-3.5 rounded-2xl mb-3 transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed">
                <span class="btn-content inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Login
                </span>
            </a>
        @endauth

        {{-- ============================================ --}}
        {{-- BANTUAN (SELALU MUNCUL)                      --}}
        {{-- ============================================ --}}
        <div class="pt-4 border-t border-slate-100">
            <p class="text-xs text-slate-500 mb-2">Ada kendala?</p>
            <button type="button" onclick="shareToWhatsApp(this)"
                class="inline-flex items-center gap-2 text-sm font-bold text-green-600 hover:text-green-700 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Salin Pesan & Buka WhatsApp
            </button>
        </div>
    </div>

    {{-- ============================================ --}}
    {{-- SCRIPT                                       --}}
    {{-- ============================================ --}}
    <script>
        // ✅ WA Message dari PHP
        const waMessage = @json($waMessage);

        /**
         * Anti-spam: disable tombol setelah diklik + tampilkan loading
         */
        function setLoading(btn, loadingText = 'Memproses...') {
            if (!btn) return;

            // Simpan state awal
            const originalHtml = btn.innerHTML;
            btn.dataset.originalHtml = originalHtml;
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');

            // Ganti content jadi loading spinner
            btn.innerHTML = `
                <span class="inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>${loadingText}</span>
                </span>
            `;
        }

        /**
         * Reset tombol ke state awal
         */
        function resetButton(btn) {
            if (!btn) return;
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            if (btn.dataset.originalHtml) {
                btn.innerHTML = btn.dataset.originalHtml;
            }
        }

        /**
         * Tombol Kembali — anti-spam
         */
        function handleBack(el) {
            // Cegah klik dobel
            if (el.dataset.loading === 'true') return false;
            el.dataset.loading = 'true';
            el.classList.add('opacity-50', 'cursor-not-allowed', 'pointer-events-none');

            // Ganti content ke loading
            const originalHtml = el.innerHTML;
            el.dataset.originalHtml = originalHtml;
            el.innerHTML = `
                <span class="inline-flex items-center justify-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Memuat...</span>
                </span>
            `;

            // Biarkan browser navigate (tidak preventDefault)
            return true;
        }

        /**
         * Share ke WhatsApp — anti-spam
         */
        function shareToWhatsApp(btn) {
            // Cegah klik dobel
            if (btn && btn.disabled) return;

            // Set loading
            if (btn) {
                setLoading(btn, 'Membuka WhatsApp...');
            }

            // Coba copy ke clipboard dulu
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(waMessage)
                    .then(() => {
                        showToast('✅ Pesan ter-copy. WhatsApp akan terbuka.');

                        // Buka WhatsApp setelah 500ms
                        setTimeout(() => {
                            window.open('https://wa.me/?text=' + encodeURIComponent(waMessage), '_blank');

                            // Reset tombol setelah 2 detik
                            setTimeout(() => {
                                if (btn) resetButton(btn);
                            }, 2000);
                        }, 500);
                    })
                    .catch(err => {
                        // Fallback: prompt copy manual
                        fallbackCopy(btn);
                    });
            } else {
                fallbackCopy(btn);
            }
        }

        /**
         * Fallback: copy manual
         */
        function fallbackCopy(btn) {
            const textarea = document.createElement('textarea');
            textarea.value = waMessage;
            textarea.style.position = 'fixed';
            textarea.style.opacity = '0';
            document.body.appendChild(textarea);
            textarea.select();

            try {
                document.execCommand('copy');
                showToast('✅ Pesan ter-copy. WhatsApp akan terbuka.');

                setTimeout(() => {
                    window.open('https://wa.me/?text=' + encodeURIComponent(waMessage), '_blank');
                    setTimeout(() => {
                        if (btn) resetButton(btn);
                    }, 2000);
                }, 500);
            } catch (err) {
                prompt('Copy pesan berikut, lalu kirim ke admin via WhatsApp:', waMessage);
                window.open('https://wa.me/', '_blank');
                if (btn) resetButton(btn);
            } finally {
                document.body.removeChild(textarea);
            }
        }

        /**
         * Toast notification sederhana
         */
        function showToast(message) {
            const oldToast = document.getElementById('custom-toast');
            if (oldToast) oldToast.remove();

            const toast = document.createElement('div');
            toast.id = 'custom-toast';
            toast.className =
                'fixed bottom-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white px-5 py-3 rounded-xl shadow-xl text-sm font-medium z-[100] transition-all';
            toast.style.opacity = '0';
            toast.textContent = message;
            document.body.appendChild(toast);

            setTimeout(() => toast.style.opacity = '1', 10);

            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        // ✅ Auto-check status (hanya kalau menunggu_verifikasi)
        @if ($pembayaran->status === 'menunggu_verifikasi')
            setInterval(async () => {
                try {
                    const res = await fetch('{{ route('qris.check', $pembayaran->order_id) }}');
                    const data = await res.json();

                    if (data.status !== 'menunggu_verifikasi') {
                        window.location.reload();
                    }
                } catch (e) {
                    console.log('Check error:', e);
                }
            }, 30000);
        @endif
    </script>

</body>

</html>