@extends('layouts.legal')

@section('title', 'Syarat Ketentuan')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 py-8 sm:py-12 mb-20">
        
        <div class="bg-white rounded-2xl sm:rounded-[2rem] shadow-sm sm:shadow-soft border border-slate-200/80 overflow-hidden">
            
            {{-- ========== HEADER DOKUMEN ========== --}}
            <div class="bg-slate-50/60 border-b border-slate-100 p-6 sm:p-10 text-center sm:text-left">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 border border-blue-100 text-blue-700 text-[11px] font-bold uppercase tracking-widest mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Legal & Kepatuhan
                </div>
                <h1 class="text-2xl sm:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Syarat & Ketentuan</h1>
                <p class="text-sm font-medium text-slate-500 flex items-center justify-center sm:justify-start gap-1.5">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Terakhir diperbarui: {{ now()->translatedFormat('d F Y') ?? now()->format('d F Y') }}
                </p>
            </div>

            {{-- ========== KONTEN DOKUMEN ========== --}}
            <div class="p-6 sm:p-10 space-y-8 sm:space-y-10">
                
                {{-- Intro Singkat --}}
                <p class="text-slate-600 text-sm sm:text-base leading-relaxed">
                    Selamat datang di <strong>Omzetly.id</strong>. Syarat dan Ketentuan ini mengatur penggunaan Anda atas platform dan layanan kami. Harap membaca dokumen ini dengan saksama sebelum mulai menggunakan aplikasi.
                </p>

                <hr class="border-slate-100">

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">1</span>
                        Penerimaan Syarat
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Dengan mendaftar, mengakses, dan menggunakan platform Omzetly.id, Anda menyatakan bahwa Anda telah membaca, memahami, dan menyetujui seluruh isi Syarat & Ketentuan ini secara sadar. Jika Anda tidak setuju dengan ketentuan ini, harap segera menghentikan penggunaan aplikasi.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">2</span>
                        Cakupan Layanan
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Omzetly.id menyediakan platform berbasis <em>cloud</em> yang dirancang khusus untuk memfasilitasi bisnis agen BRILink dan retail, yang mencakup layanan:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li><strong class="text-slate-700">Pencatatan Transaksi:</strong> Modul pencatatan pemasukan, pengeluaran, transfer, tarik tunai, dan pembayaran tagihan.</li>
                            <li><strong class="text-slate-700">Kalkulasi Keuangan:</strong> Perhitungan otomatis laba rugi, mutasi saldo, dan potongan biaya admin bank.</li>
                            <li><strong class="text-slate-700">Multi-Tenancy:</strong> Manajemen operasional untuk banyak cabang dalam satu ekosistem akun terpadu.</li>
                            <li><strong class="text-slate-700">Pelaporan:</strong> Pembuatan laporan ringkasan keuangan harian, mingguan, dan bulanan.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">3</span>
                        Kewajiban Pengguna
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Selama menggunakan layanan kami, Anda diwajibkan untuk:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Memberikan data registrasi yang valid, akurat, dan dapat dipertanggungjawabkan.</li>
                            <li>Menjaga kerahasiaan kredensial login (email dan password) dan tidak membagikannya kepada pihak yang tidak memiliki wewenang operasional.</li>
                            <li>Tidak menggunakan aplikasi Omzetly.id untuk memfasilitasi kegiatan yang melanggar hukum, penipuan pencucian uang, atau tindak kriminal lainnya.</li>
                            <li>Menggunakan layanan sesuai dengan kapasitas paket berlangganan yang telah dipilih.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">4</span>
                        Langganan & Pembayaran
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-4">Penggunaan fitur premium tunduk pada kewajiban pembayaran biaya berlangganan. Ketentuan yang berlaku:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg mb-4">
                            <li>Semua paket langganan bersifat <strong>prabayar</strong> dan diproses secara aman melalui <em>payment gateway</em> resmi (Midtrans).</li>
                            <li>Sistem secara otomatis dapat membatasi atau menonaktifkan akses ke fitur tertentu apabila tagihan langganan telah jatuh tempo dan belum dilunasi.</li>
                        </ul>
                        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-amber-800 flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <p class="font-medium text-sm"><strong>Kebijakan Non-Refund:</strong> Seluruh pembayaran langganan yang telah sukses terkonfirmasi <strong>tidak dapat dikembalikan (refund)</strong> dengan alasan apa pun, termasuk jika Anda memutuskan untuk berhenti menggunakan aplikasi sebelum masa aktif berakhir.</p>
                        </div>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">5</span>
                        Batasan Tanggung Jawab
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Meskipun kami berkomitmen memberikan sistem terbaik, Omzetly.id <strong>tidak bertanggung jawab</strong> atas:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Kerugian finansial yang diakibatkan oleh <em>human error</em> atau kesalahan <em>input</em> nominal transaksi oleh operator/kasir cabang Anda.</li>
                            <li>Kehilangan atau kebocoran data operasional yang terjadi akibat kelalaian Anda dalam menjaga kerahasiaan kata sandi perangkat.</li>
                            <li>Gangguan layanan (<em>downtime</em>) yang disebabkan oleh keadaan memaksa di luar kendali kami (<em>Force Majeure</em>), seperti gangguan pada penyedia server pusat atau koneksi internet lokal Anda.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">6</span>
                        Penghentian Akses & Akun
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p class="mb-3">Omzetly.id berhak secara sepihak untuk menangguhkan atau menghapus akun Anda tanpa pemberitahuan sebelumnya apabila kami menemukan bukti bahwa:</p>
                        <ul class="list-disc pl-5 space-y-2 marker:text-blue-500 marker:text-lg">
                            <li>Terjadi pelanggaran serius terhadap Syarat & Ketentuan ini.</li>
                            <li>Terdapat indikasi penggunaan aplikasi untuk memfasilitasi tindakan penipuan atau ilegal.</li>
                            <li>Akun menunggak pembayaran langganan melewati batas toleransi yang diberikan.</li>
                        </ul>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">7</span>
                        Perubahan Syarat & Ketentuan
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Kami berhak merevisi atau mengubah Syarat & Ketentuan ini sewaktu-waktu guna menyesuaikan dengan pembaruan sistem atau regulasi hukum. Setiap perubahan akan diumumkan langsung di halaman ini, dan kami menyarankan Anda untuk meninjaunya secara berkala.</p>
                    </div>
                </section>

                <section>
                    <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 mb-3 flex items-center gap-3">
                        <span class="w-8 h-8 shrink-0 rounded-lg bg-blue-50 border border-blue-100 text-blue-600 flex items-center justify-center text-sm font-black">8</span>
                        Layanan Kontak Bantuan
                    </h2>
                    <div class="pl-0 sm:pl-11 text-slate-600 text-sm sm:text-base leading-relaxed">
                        <p>Jika Anda memiliki pertanyaan mendetail atau membutuhkan klarifikasi terkait Syarat & Ketentuan ini, jangan ragu untuk menghubungi tim hukum dan dukungan kami melalui alamat email: <a href="mailto:support@omzetly.id" class="text-blue-600 font-bold hover:text-blue-700 hover:underline underline-offset-4 transition-all">support@omzetly.id</a>.</p>
                    </div>
                </section>

            </div>
        </div>
    </div>
@endsection