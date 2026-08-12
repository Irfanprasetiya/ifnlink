@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="safe-bottom pb-24 mt-2 px-1 sm:px-2">
        <div class="max-w-7xl mx-auto">

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">

                <!-- KOLOM KIRI (Form Utama) -->
                <div class="lg:col-span-8 xl:col-span-9">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden relative">

                        <div
                            class="absolute -top-10 -right-10 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
                        </div>

                        <!-- Header Form -->
                        <div class="border-b border-slate-100 px-4 py-4 sm:px-6 sm:py-5 relative z-10">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5">
                                    <div
                                        class="w-10 h-10 sm:w-11 sm:h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-lg sm:text-xl font-extrabold text-slate-800 tracking-tight">Form
                                            Transaksi</h2>
                                        <p class="text-[11px] sm:text-xs font-medium text-slate-500 mt-0.5">Lengkapi form di
                                            bawah untuk mencatat transaksi baru</p>
                                    </div>
                                </div>
                                <div
                                    class="flex items-center gap-3 bg-slate-50/50 p-2.5 sm:p-3 rounded-xl border border-slate-100 sm:w-auto w-full">
                                    <div
                                        class="w-9 h-9 sm:w-10 sm:h-10 bg-white shadow-sm border border-slate-200 rounded-lg flex items-center justify-center shrink-0">
                                        <span
                                            class="text-blue-600 font-bold text-sm sm:text-base">{{ strtoupper(substr($bank->nama_bank, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0 pr-2">
                                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-wider mb-0.5">Bank
                                            Tujuan</p>
                                        <p class="font-bold text-slate-800 text-xs sm:text-sm truncate">
                                            {{ $bank->nama_bank }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Content -->
                        <div class="p-4 sm:p-6 relative z-10">
                            <form action="{{ route('transaksi_banks.store') }}" method="POST"
                                class="space-y-4 sm:space-y-5" id="formTransaksiBank">
                                @csrf
                                <input type="hidden" name="bank_id" value="{{ $bank->id }}">
                                <input type="hidden" name="waktu_transaksi" value="{{ now() }}">

                                @if (session('error'))
                                    <div
                                        class="flex items-start gap-3 p-3.5 mb-2 text-rose-800 rounded-xl bg-rose-50 border border-rose-200/80 shadow-sm">
                                        <svg class="w-5 h-5 shrink-0 mt-0.5 text-rose-500" fill="currentColor"
                                            viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        <span
                                            class="font-medium text-xs sm:text-sm leading-relaxed">{{ session('error') }}</span>
                                    </div>
                                @endif

                                <div class="grid gap-4 sm:gap-5 md:grid-cols-2">

                                    <!-- Jenis Transaksi -->
                                    <div class="md:col-span-2">
                                        <label for="jenis_transaksi_id"
                                            class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Jenis Transaksi <span class="text-rose-500">*</span>
                                        </label>
                                        <select name="jenis_transaksi_id" id="jenis_transaksi_id"
                                            class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block w-full p-3 transition-all duration-200 font-medium appearance-none"
                                            required>
                                            <option value="" disabled
                                                {{ old('jenis_transaksi_id') ? '' : 'selected' }}>-- Pilih Jenis Transaksi
                                                --</option>
                                            @foreach ($jenisTransaksis as $jenis)
                                                <option value="{{ $jenis->id }}"
                                                    data-nama="{{ strtolower($jenis->nama_transaksi) }}"
                                                    {{ old('jenis_transaksi_id') == $jenis->id ? 'selected' : '' }}>
                                                    {{ $jenis->nama_transaksi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('jenis_transaksi_id')
                                            <p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- No Tujuan -->
                                    <div id="field_no_tujuan" class="hidden md:col-span-2">
                                        <label for="no_tujuan"
                                            class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            No. Tujuan / Rekening <span
                                                class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span>
                                        </label>
                                        <input type="text" name="no_tujuan" id="no_tujuan"
                                            value="{{ old('no_tujuan') }}"
                                            class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block w-full p-3 transition-all duration-200 font-medium placeholder:text-slate-400 placeholder:font-normal"
                                            placeholder="Cth: 08123456789 atau 1234567890">
                                        @error('no_tujuan')
                                            <p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Nominal -->
                                    <div id="field_nominal" class="hidden">
                                        <label for="nominal"
                                            class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Nominal Transaksi (EDC/Aplikasi)
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                                <span class="text-slate-500 font-bold text-sm">Rp</span>
                                            </div>
                                            <input type="text" inputmode="numeric" name="nominal" id="nominal"
                                                value="{{ old('nominal') }}"
                                                class="currency-input bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block w-full pl-10 p-3 transition-all duration-200 font-bold placeholder:text-slate-300 placeholder:font-normal"
                                                placeholder="0">
                                        </div>
                                        @error('nominal')
                                            <p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Bayar -->
                                    <div id="field_bayar" class="hidden md:col-span-2">
                                        <label for="bayar" id="label_bayar"
                                            class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Total Diterima/Dibayar <span class="text-rose-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                                <span
                                                    class="text-emerald-600 font-bold text-sm sm:text-base transition-colors group-focus-within:text-emerald-700">Rp</span>
                                            </div>
                                            <input type="text" inputmode="numeric" name="bayar" id="bayar"
                                                value="{{ old('bayar') }}"
                                                class="currency-input bg-emerald-50/50 border border-emerald-200 text-emerald-900 text-sm sm:text-base rounded-xl focus:bg-white focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 block w-full pl-10 sm:pl-11 p-3 transition-all duration-200 font-extrabold placeholder:text-emerald-300 placeholder:font-normal"
                                                placeholder="0" required>
                                        </div>
                                        @error('bayar')
                                            <p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>
                                        @enderror

                                        {{-- ✅ INFO BIAYA ADMIN (Realtime) --}}
                                        <div id="info_biaya_admin"
                                            class="hidden mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm">
                                            <div class="flex items-center justify-between">
                                                <span class="text-amber-700 font-medium">💰 Biaya Admin</span>
                                                <span id="biaya_admin_value" class="text-amber-800 font-bold">Rp 0</span>
                                            </div>
                                            <p id="biaya_admin_desc" class="text-[11px] text-amber-600 mt-1"></p>
                                        </div>
                                    </div>

                                    <!-- Keterangan -->
                                    <div class="md:col-span-2">
                                        <label for="keterangan"
                                            class="block mb-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                                            Catatan Singkat <span
                                                class="text-slate-400 font-medium normal-case tracking-normal">(Opsional)</span>
                                        </label>
                                        <textarea name="keterangan" id="keterangan" rows="2"
                                            class="bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:bg-white focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 block w-full p-3 transition-all duration-200 font-medium placeholder:text-slate-400 placeholder:font-normal resize-none"
                                            placeholder="Cth: Bayar tagihan listrik bulan ini...">{{ old('keterangan') }}</textarea>
                                        @error('keterangan')
                                            <p class="mt-1.5 text-xs font-medium text-rose-500">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="h-px bg-slate-100 my-5"></div>

                                <div class="flex justify-end">
                                    <button type="submit" id="btnSimpan"
                                        class="w-full sm:w-auto inline-flex justify-center items-center gap-2 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:ring-blue-500/20 font-bold rounded-xl text-sm px-6 py-3 transition-all duration-200 shadow-sm shadow-blue-500/30 active:scale-95">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                        </svg>
                                        Simpan Transaksi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN (Panduan) -->
                <div class="lg:col-span-4 xl:col-span-3 mt-4 lg:mt-0">
                    <div class="sticky top-6">
                        <div class="bg-blue-50/50 rounded-2xl border border-blue-100/80 p-4 sm:p-5">
                            <div class="flex items-center gap-2 mb-3">
                                <div
                                    class="w-7 h-7 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center shrink-0">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <h3 class="font-extrabold text-blue-900 text-sm">Panduan Pengisian</h3>
                            </div>
                            <ul class="space-y-2.5">
                                <li
                                    class="flex items-start gap-2 text-[11px] sm:text-xs text-blue-800 font-medium leading-relaxed">
                                    <span class="text-blue-500 font-bold shrink-0 mt-0.5">•</span>
                                    <span>Pilih <strong>Jenis Transaksi</strong> terlebih dahulu agar form input terkait
                                        muncul secara otomatis.</span>
                                </li>
                                <li
                                    class="flex items-start gap-2 text-[11px] sm:text-xs text-blue-800 font-medium leading-relaxed">
                                    <span class="text-blue-500 font-bold shrink-0 mt-0.5">•</span>
                                    <span>Kolom <strong>No. Tujuan</strong> dan <strong>Catatan</strong> tidak wajib diisi
                                        (opsional).</span>
                                </li>
                                <li
                                    class="flex items-start gap-2 text-[11px] sm:text-xs text-blue-800 font-medium leading-relaxed">
                                    <span class="text-blue-500 font-bold shrink-0 mt-0.5">•</span>
                                    <span>Khusus transaksi <em>Numpang Transfer</em>, Anda cukup memasukkan nilai
                                        <strong>Total Bayar</strong> saja.</span>
                                </li>
                                <li
                                    class="flex items-start gap-2 text-[11px] sm:text-xs text-blue-800 font-medium leading-relaxed">
                                    <span class="text-blue-500 font-bold shrink-0 mt-0.5">•</span>
                                    <span>Untuk transaksi <strong>Topup E-Wallet</strong>, <strong>Pembayaran
                                            Tagihan</strong>, atau <strong>Isi Pulsa</strong>, silakan pilih jenis transaksi
                                        <strong>Transfer</strong>.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <script>
        function formatCurrencyInput(inputElement) {
            inputElement.addEventListener('input', function(e) {
                if (this.value === '') return;
                let cursorPos = this.selectionStart;
                let originalLength = this.value.length;
                let raw = this.value.replace(/\D/g, '');
                let formatted = raw ? new Intl.NumberFormat('id-ID').format(raw) : '';
                this.value = formatted;
                let newLength = this.value.length;
                let diff = newLength - originalLength;
                try {
                    this.setSelectionRange(cursorPos + diff, cursorPos + diff);
                } catch (e) {}
            });
        }

        function parseCurrency(value) {
            return parseInt((value || '').replace(/\D/g, '')) || 0;
        }

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // ✅ HITUNG BIAYA ADMIN REALTIME
        function hitungBiayaAdmin() {
            const jenisSelect = document.getElementById('jenis_transaksi_id');
            const selected = jenisSelect.options[jenisSelect.selectedIndex];
            const nama = selected ? selected.getAttribute('data-nama') : '';

            const nominal = parseCurrency(document.getElementById('nominal').value);
            const bayar = parseCurrency(document.getElementById('bayar').value);

            const infoBox = document.getElementById('info_biaya_admin');
            const biayaValue = document.getElementById('biaya_admin_value');
            const biayaDesc = document.getElementById('biaya_admin_desc');

            if (!nama || nominal <= 0 || bayar <= 0) {
                infoBox.classList.add('hidden');
                return;
            }

            let biayaAdmin = 0;

            if (nama === 'transfer') {
                biayaAdmin = bayar - nominal;
                biayaDesc.textContent = biayaAdmin >= 0 ? 'Total bayar lebih besar dari nominal' :
                    'Nominal lebih besar dari bayar';
            } else if (nama === 'tarik tunai') {
                biayaAdmin = nominal - bayar;
                biayaDesc.textContent = biayaAdmin >= 0 ? 'Nominal lebih besar dari bayar' :
                    'Total bayar lebih besar dari nominal';
            } else if (nama === 'numpang transfer') {
                biayaAdmin = bayar;
                biayaDesc.textContent = 'Biaya jasa numpang transfer';
            } else {
                infoBox.classList.add('hidden');
                return;
            }

            infoBox.classList.remove('hidden');

            if (biayaAdmin >= 0) {
                biayaValue.textContent = 'Rp ' + formatRupiah(biayaAdmin);
                infoBox.className = 'mt-2 p-3 bg-amber-50 border border-amber-200 rounded-xl text-sm';
                biayaValue.className = 'text-amber-800 font-bold';
            } else {
                biayaValue.textContent = '- Rp ' + formatRupiah(Math.abs(biayaAdmin));
                infoBox.className = 'mt-2 p-3 bg-red-50 border border-red-200 rounded-xl text-sm';
                biayaValue.className = 'text-red-600 font-bold';
                biayaDesc.textContent = '⚠️ Jumlah tidak valid, periksa kembali!';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.currency-input').forEach(function(input) {
                formatCurrencyInput(input);
                if (input.value) input.dispatchEvent(new Event('input'));
            });

            const jenisSelect = document.getElementById('jenis_transaksi_id');
            const fieldNoTujuan = document.getElementById('field_no_tujuan');
            const fieldNominal = document.getElementById('field_nominal');
            const fieldBayar = document.getElementById('field_bayar');
            const inputNominal = document.getElementById('nominal');
            const inputBayar = document.getElementById('bayar');
            const inputNoTujuan = document.getElementById('no_tujuan');

            function toggleFields() {
                const selected = jenisSelect.options[jenisSelect.selectedIndex];
                const nama = selected ? selected.getAttribute('data-nama') : '';

                fieldNoTujuan.classList.add('hidden');
                fieldNominal.classList.add('hidden');
                fieldBayar.classList.add('hidden');
                document.getElementById('info_biaya_admin').classList.add('hidden');

                if (nama === '') return;

                if (nama === 'numpang transfer') {
                    fieldBayar.classList.remove('hidden');
                    fieldBayar.classList.add('md:col-span-2');
                    inputNominal.required = false;
                    inputNoTujuan.required = false;
                    inputBayar.required = true;
                } else if (nama === 'tarik tunai' || nama === 'transfer') {
                    fieldNoTujuan.classList.remove('hidden');
                    fieldNominal.classList.remove('hidden');
                    fieldBayar.classList.remove('hidden');
                    fieldBayar.classList.remove('md:col-span-2');
                    inputNominal.required = true;
                    inputNoTujuan.required = false;
                    inputBayar.required = true;
                } else {
                    fieldNoTujuan.classList.remove('hidden');
                    fieldNominal.classList.remove('hidden');
                    fieldBayar.classList.remove('hidden');
                    inputNoTujuan.required = false;
                }

                hitungBiayaAdmin();
            }

            jenisSelect.addEventListener('change', toggleFields);

            // ✅ Hitung ulang saat nominal atau bayar berubah
            inputNominal.addEventListener('input', hitungBiayaAdmin);
            inputBayar.addEventListener('input', hitungBiayaAdmin);

            toggleFields();

            document.getElementById('formTransaksiBank').addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');
                if (btn.dataset.submitting === 'true') {
                    e.preventDefault();
                    return;
                }
                btn.dataset.submitting = 'true';
                this.querySelectorAll('.currency-input').forEach(function(input) {
                    input.value = input.value.replace(/\D/g, '');
                });
                btn.classList.add('opacity-75', 'cursor-not-allowed', 'pointer-events-none');
                btn.innerHTML =
                    `<svg class="animate-spin h-5 w-5 text-white shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...`;
            });
        });
    </script>
@endsection
