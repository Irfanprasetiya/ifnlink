@extends('layouts.legal')

@section('title', 'Syarat Ketentuan')

@section('content')
    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-10">
        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mb-2">Syarat & Ketentuan</h1>
        <p class="text-xs text-slate-400 mb-8">Terakhir diperbarui: {{ now()->format('d F Y') }}</p>

        <div class="space-y-8 text-sm leading-relaxed">
            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">1. Penerimaan Syarat</h2>
                <p class="text-slate-600">Dengan mendaftar dan menggunakan Omzetly.id, Anda menyetujui syarat & ketentuan
                    ini. Jika tidak setuju, harap berhenti menggunakan aplikasi.</p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">2. Layanan</h2>
                <p class="text-slate-600">Omzetly.id menyediakan platform untuk:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Pencatatan transaksi keuangan agen</li>
                    <li>Perhitungan laba rugi</li>
                    <li>Manajemen cabang dan user</li>
                    <li>Laporan keuangan</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">3. Kewajiban Pengguna</h2>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Memberikan data yang benar dan akurat</li>
                    <li>Menjaga kerahasiaan password</li>
                    <li>Tidak menggunakan aplikasi untuk kegiatan ilegal</li>
                    <li>Tidak membagikan akses ke pihak yang tidak berwenang</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">4. Langganan & Pembayaran</h2>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Langganan bersifat prabayar</li>
                    <li>Pembayaran dilakukan via Midtrans</li>
                    <li>Tidak ada refund setelah pembayaran berhasil</li>
                    <li>Akun bisa dinonaktifkan jika tidak membayar</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">5. Batasan Tanggung Jawab</h2>
                <p class="text-slate-600">Omzetly.id tidak bertanggung jawab atas:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Kerugian akibat kesalahan input pengguna</li>
                    <li>Kehilangan data akibat kelalaian pengguna</li>
                    <li>Gangguan layanan di luar kendali kami</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">6. Penghentian Akun</h2>
                <p class="text-slate-600">Kami berhak menonaktifkan akun jika:</p>
                <ul class="list-disc pl-5 mt-2 space-y-1 text-slate-600">
                    <li>Melanggar syarat ketentuan</li>
                    <li>Terindikasi penipuan</li>
                    <li>Tidak membayar langganan</li>
                </ul>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">7. Perubahan Syarat</h2>
                <p class="text-slate-600">Kami dapat mengubah syarat ketentuan sewaktu-waktu. Perubahan akan diumumkan di
                    halaman ini.</p>
            </section>

            <section>
                <h2 class="text-lg font-bold text-slate-800 mb-2">8. Kontak</h2>
                <p class="text-slate-600">Pertanyaan? Hubungi: <a href="mailto:support@omzetly.id"
                        class="text-blue-600 hover:underline">support@omzetly.id</a></p>
            </section>
        </div>
    </div>
@endsection
