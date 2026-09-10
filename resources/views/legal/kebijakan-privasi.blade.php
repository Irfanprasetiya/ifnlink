@extends('layouts.legal')

@section('title', 'Kebijakan Privasi')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12 mb-20">
        
        <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm sm:shadow-soft border border-slate-200/80 overflow-hidden">
            
            {{-- ========== HEADER DOKUMEN ========== --}}
            <div class="bg-slate-50/60 border-b border-slate-100 p-6 sm:p-10 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-[11px] font-bold uppercase tracking-widest mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    Legal & Keamanan
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Kebijakan Privasi</h1>
                <p class="text-sm font-medium text-slate-500 flex items-center justify-center sm:justify-start gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y') ?? now()->format('d F Y') }}
                </p>
            </div>

            {{-- ========== KONTEN DOKUMEN ========== --}}
            <div class="p-6 sm:p-10 space-y-8 sm:space-y-10">
                
                {{-- Intro Singkat (Opsional tapi bagus untuk transisi) --}}
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Privasi dan keamanan data Anda adalah prioritas utama kami. Dokumen Kebijakan Privasi ini menjelaskan secara transparan bagaimana <strong>Omzetly.id</strong> mengumpulkan, menggunakan, membagikan, dan melindungi informasi pribadi Anda saat menggunakan layanan kami.
                </p>

                <hr class="border-slate-100">

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">1</span>
                        Informasi yang Kami Kumpulkan
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Omzetly.id secara spesifik mengumpulkan informasi berikut dari pengguna:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li><strong class="text-slate-700">Data Identitas:</strong> Nama lengkap, alamat email, dan nomor Handphone saat proses registrasi.</li>
                            <li><strong class="text-slate-700">Data Transaksional:</strong> Riwayat pencatatan keuangan termasuk pemasukan, pengeluaran, tarik/setor tunai, dan mutasi saldo cabang.</li>
                            <li><strong class="text-slate-700">Data Penggunaan:</strong> Analitik aktivitas aplikasi seperti halaman yang sering diakses dan fitur yang digunakan.</li>
                            <li><strong class="text-slate-700">Data Teknis:</strong> Informasi perangkat seperti jenis browser, sistem operasi, dan alamat IP demi alasan keamanan.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">2</span>
                        Cara Kami Menggunakan Data
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Data yang kami kumpulkan semata-mata digunakan untuk tujuan operasional dan peningkatan layanan, meliputi:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Memfasilitasi dan memproses transaksi pencatatan keuangan agen Anda.</li>
                            <li>Mengirimkan notifikasi penting terkait aktivitas cabang dan informasi akun.</li>
                            <li>Menganalisis pola penggunaan untuk meningkatkan kualitas dan fitur sistem.</li>
                            <li>Menjaga keamanan akun dan mencegah aktivitas penipuan (fraud).</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">3</span>
                        Pembagian Data ke Pihak Ketiga
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 mb-4 text-emerald-800 flex gap-3">
                            <svg class="w-5 h-5 text-emerald-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="font-medium">Kami memiliki komitmen ketat bahwa kami <strong>TIDAK PERNAH</strong> menjual atau menyewakan data pribadi Anda kepada pihak mana pun.</p>
                        </div>
                        <p class="mb-3">Data Anda hanya akan dibagikan dalam kondisi terbatas kepada:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Layanan <em>Payment Gateway</em> (seperti Midtrans) secara eksklusif untuk memproses pembayaran biaya langganan aplikasi.</li>
                            <li>Pihak berwenang atau aparat penegak hukum apabila diwajibkan oleh peraturan perundang-undangan yang berlaku di Indonesia.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">4</span>
                        Keamanan Data
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Kami menerapkan standar keamanan industri terkini. Seluruh komunikasi data dilindungi oleh enkripsi <strong>SSL/TLS</strong>. Kata sandi (password) Anda disimpan dalam bentuk terenkripsi satu arah (hash) dan tidak dapat dibaca oleh siapa pun, termasuk administrator kami. Data pencatatan transaksi masing-masing cabang terisolasi dan hanya dapat diakses oleh akun pengguna (owner/operator) yang berwenang.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">5</span>
                        Hak Anda Sebagai Pengguna
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Anda memiliki kendali penuh atas data Anda. Kapan saja, Anda berhak untuk:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Mengakses, meninjau, dan mengubah data profil maupun data transaksi Anda.</li>
                            <li>Meminta penghapusan akun beserta seluruh riwayat data terkait dari server kami secara permanen.</li>
                            <li>Menarik persetujuan Anda atas penggunaan data tertentu.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">6</span>
                        Penggunaan Cookie
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Kami menggunakan <em>cookie</em> semata-mata untuk menjaga sesi login Anda tetap aktif dan menyimpan preferensi tampilan aplikasi. Kami <strong>tidak</strong> menggunakan cookie pihak ketiga untuk melacak aktivitas penjelajahan Anda di luar situs Omzetly.id.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">7</span>
                        Hubungi Kami
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Jika Anda memiliki pertanyaan, masukan, atau kekhawatiran terkait Kebijakan Privasi ini atau cara kami menangani data Anda, silakan hubungi tim dukungan kami melalui email di <a href="mailto:support@omzetly.id" class="text-blue-600 font-bold hover:text-blue-700 hover:underline underline-offset-4 transition-all">support@omzetly.id</a>.</p>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection