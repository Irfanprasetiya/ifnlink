@extends('layouts.legal')

@section('title', 'Kebijakan Privasi')

@section('content')
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-10">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">Kebijakan Privasi</h1>
        <p class="text-xs text-slate-400 mb-8">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>

        <div class="space-y-8 text-sm leading-relaxed">
            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">1. Informasi yang Kami Kumpulkan</h2>
                <p class="text-slate-600">Omzetly.id mengumpulkan informasi berikut:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Nama lengkap, email, dan nomor HP saat registrasi</li>
                    <li>Data transaksi keuangan (pemasukan, pengeluaran, transfer)</li>
                    <li>Data penggunaan aplikasi (halaman yang diakses, fitur yang dipakai)</li>
                    <li>Informasi perangkat (browser, IP address)</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">2. Cara Kami Menggunakan Data</h2>
                <p class="text-slate-600">Data yang kami kumpulkan digunakan untuk:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Memproses transaksi keuangan Anda</li>
                    <li>Mengirim notifikasi transaksi dan informasi penting</li>
                    <li>Meningkatkan kualitas layanan</li>
                    <li>Keamanan dan pencegahan penipuan</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">3. Pembagian Data ke Pihak Ketiga</h2>
                <p class="text-slate-600">Kami TIDAK menjual data Anda. Data hanya dibagikan ke:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Midtrans (payment gateway) untuk proses pembayaran</li>
                    <li>Pihak berwenang jika diwajibkan oleh hukum</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">4. Keamanan Data</h2>
                <p class="text-slate-600">Kami menggunakan enkripsi SSL untuk melindungi data. Password disimpan terenkripsi
                    (hash). Data keuangan hanya diakses oleh pengguna yang berwenang.</p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">5. Hak Anda</h2>
                <p class="text-slate-600">Anda berhak untuk:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Mengakses dan mengubah data pribadi</li>
                    <li>Menghapus akun</li>
                    <li>Menarik persetujuan penggunaan data</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">6. Cookie</h2>
                <p class="text-slate-600">Kami menggunakan cookie untuk menyimpan sesi login dan preferensi pengguna. Cookie
                    tidak digunakan untuk melacak aktivitas di luar Omzetly.id.</p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">7. Kontak</h2>
                <p class="text-slate-600">Pertanyaan tentang kebijakan privasi? Hubungi kami di <a
                        href="mailto:support@omzetly.id" class="text-blue-600 hover:underline">support@omzetly.id</a></p>
            </section>
        </div>
    </div>
@endsection
