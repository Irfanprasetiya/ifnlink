@extends('layouts.app')

@section('title', 'Tambah Saldo Awal')

@section('container')

    <div class="w-full max-w-full overflow-x-hidden space-y-6 pb-12 mt-5">

        {{-- Header & Title Area --}}
        <div
            class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-5 sm:p-6 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
            <div
                class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl opacity-60 pointer-events-none">
            </div>

            <div class="relative z-10 w-full">
                <h1 class="text-xl sm:text-2xl font-bold text-slate-800 tracking-tight flex items-center gap-2.5">
                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg shrink-0">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </span>
                    Saldo Awal
                </h1>
                {{-- Deskripsi disembunyikan di HP (hidden sm:block) --}}
                <p class="text-sm text-slate-500 mt-2 font-medium hidden sm:block">
                    Atur nominal saldo awal untuk masing-masing akun bank cabang sebelum melakukan transaksi.
                </p>
            </div>
        </div>

        {{-- Alerts (Konsisten dengan halaman sebelumnya) --}}
        @if (session('success'))
            <div
                class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div>
                    <span class="font-bold block mb-0.5">Berhasil disimpan!</span>
                    <span class="opacity-90">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div
                class="bg-rose-50 border border-rose-200 text-rose-800 px-5 py-3.5 rounded-xl text-sm flex items-center gap-3 shadow-sm">
                <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <span class="font-bold block mb-0.5">Terjadi Kesalahan!</span>
                    <span class="opacity-90">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <form action="{{ route('saldo.store') }}" method="POST" id="formSaldoAwal" class="space-y-6">
            @csrf

            {{-- STEP 1: Pemilihan Cabang --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-blue-600"></div>

                <div class="p-5 sm:p-6">
                    <div class="w-full sm:w-2/3 lg:w-1/2">
                        <label for="input_cabang_user" class="block text-sm font-bold text-slate-800 mb-2">
                            1. Tentukan Cabang & Akun Pengguna
                        </label>
                        <div class="relative mt-1">
                            <select name="cabang_user" id="input_cabang_user" required
                                class="appearance-none block w-full pl-4 pr-10 py-2.5 text-sm border border-slate-300 rounded-lg bg-slate-50 text-slate-900 focus:ring-blue-500 focus:border-blue-500 transition-colors cursor-pointer font-medium outline-none">
                                <option value="" disabled selected>-- Pilih cabang dan akun dari daftar --</option>
                                @foreach ($cabangs as $cabang)
                                    <optgroup label="🏢 {{ $cabang->nama_cabang }}"
                                        class="font-bold text-slate-900 bg-white">
                                        @foreach ($cabang->akuns as $akun)
                                            <option value="{{ $cabang->id }}|{{ $akun->id }}"
                                                data-status="{{ $akun->status_saldo_awal }}"
                                                class="font-normal text-slate-700">
                                                {{ $cabang->nama_cabang }} — {{ $akun->name }}
                                                ({{ $akun->status_saldo_awal == 'tersimpan' ? '✅' : '❌' }})
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div
                                class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STEP 2: Input Nominal Grid --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div
                    class="px-5 py-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:justify-between items-start sm:items-center gap-3">
                    <h2 class="text-sm font-bold text-slate-800">
                        2. Masukkan Nominal Saldo
                    </h2>
                    <span id="statusIndicator"
                        class="inline-flex items-center gap-1.5 text-[11px] font-bold uppercase tracking-wide text-slate-500 bg-slate-100 border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        Pilih akun terlebih dahulu
                    </span>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                        @foreach ($banks as $bank)
                            <div class="bank-wrapper group flex flex-col relative transition-all"
                                id="wrapper_bank_{{ $bank->id }}">
                                <div class="flex items-center justify-between mb-1.5">
                                    <label for="input_bank_{{ $bank->id }}"
                                        class="text-sm font-bold text-slate-700 flex items-center gap-2">
                                        <span
                                            class="w-6 h-6 rounded bg-blue-50 text-blue-600 flex items-center justify-center text-xs shrink-0 border border-blue-100 transition-colors group-hover:bg-blue-100">
                                            {{ substr($bank->nama_bank, 0, 1) }}
                                        </span>
                                        {{ $bank->nama_bank }}
                                    </label>

                                    {{-- Status Tersimpan --}}
                                    <span
                                        class="hidden items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100 badge-tersimpan"
                                        id="badge_bank_{{ $bank->id }}">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tersimpan
                                    </span>
                                </div>

                                <div class="relative mt-auto">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span class="text-slate-500 font-bold text-sm">Rp</span>
                                    </div>
                                    <input type="text" inputmode="numeric" name="saldo[{{ $bank->id }}]"
                                        id="input_bank_{{ $bank->id }}"
                                        class="currency-input block w-full pl-9 pr-3 py-2.5 text-sm text-slate-900 bg-slate-50 border border-slate-300 rounded-lg focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition-all font-bold outline-none shadow-sm
                                        read-only:bg-slate-100 read-only:text-slate-500 read-only:border-slate-200 read-only:shadow-none read-only:cursor-not-allowed"
                                        placeholder="0">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Footer Action Area --}}
                <div class="px-5 py-4 bg-slate-50 border-t border-slate-200 flex justify-end">
                    <button type="submit" id="submitButton" disabled
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-2.5 text-sm font-bold text-white rounded-lg bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-300 transition-all shadow-sm
                        disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:bg-blue-600">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function formatCurrencyInput(inputElement) {
            inputElement.addEventListener('input', function() {
                if (this.readOnly) return;

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

        function resetAllBankInputs() {
            document.querySelectorAll('.currency-input').forEach(function(input) {
                input.value = '';
                input.readOnly = false;
            });
            document.querySelectorAll('.badge-tersimpan').forEach(function(badge) {
                badge.classList.add('hidden');
                badge.classList.remove('flex');
            });
            document.querySelectorAll('.bank-wrapper').forEach(function(wrapper) {
                wrapper.classList.remove('opacity-60');
            });
        }

        function updateSubmitButton() {
            const submitBtn = document.getElementById('submitButton');
            const allInputs = document.querySelectorAll('.currency-input');
            const cabangUserSelect = document.getElementById('input_cabang_user');
            const statusIndicator = document.getElementById('statusIndicator');

            // Jika belum pilih akun, disable tombol
            if (!cabangUserSelect.value) {
                submitBtn.disabled = true;
                statusIndicator.innerHTML = `
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    Pilih akun terlebih dahulu
                `;
                return;
            }

            // Cek apakah ada input yang bisa diedit (tidak readonly)
            let hasEditableInput = false;
            let hasFilledInput = false;

            allInputs.forEach(function(input) {
                if (!input.readOnly) {
                    hasEditableInput = true;
                } else if (input.readOnly && input.value) {
                    hasFilledInput = true;
                }
            });

            // Jika semua input readonly (sudah tersimpan semua)
            if (!hasEditableInput) {
                submitBtn.disabled = true;
                statusIndicator.innerHTML = `
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                    <span class="text-emerald-700">Semua saldo tersimpan</span>
                `;
            } else if (hasEditableInput && hasFilledInput) {
                // Sebagian sudah diisi, sebagian belum
                submitBtn.disabled = false;
                statusIndicator.innerHTML = `
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span class="text-amber-700">Beberapa bank tersimpan</span>
                `;
            } else {
                // Semua bisa diisi
                submitBtn.disabled = false;
                statusIndicator.innerHTML = `
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span class="text-blue-700">Silakan isi nominal</span>
                `;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const submitBtn = document.getElementById('submitButton');

            // Inisialisasi: tombol disabled karena belum ada yang dipilih
            submitBtn.disabled = true;

            document.querySelectorAll('.currency-input').forEach(function(input) {
                formatCurrencyInput(input);
                // Update tombol setiap kali input berubah
                input.addEventListener('input', updateSubmitButton);
            });

            const cabangUserSelect = document.getElementById('input_cabang_user');

            cabangUserSelect.addEventListener('change', function() {
                resetAllBankInputs();
                updateSubmitButton();

                if (!this.value) return;

                const [cabangId, userId] = this.value.split('|');
                const selectedOption = this.options[this.selectedIndex];
                const status = selectedOption.getAttribute('data-status');

                document.body.style.cursor = 'wait';

                fetch(`/saldo-awal/cek/${cabangId}/${userId}?_=${Date.now()}`)
                    .then(res => res.json())
                    .then(data => {
                        const bankIds = Object.keys(data);

                        if (bankIds.length > 0) {
                            bankIds.forEach(function(bankId) {
                                const input = document.getElementById('input_bank_' + bankId);
                                const badge = document.getElementById('badge_bank_' + bankId);
                                const wrapper = document.getElementById('wrapper_bank_' +
                                    bankId);

                                if (input && data[bankId] > 0) {
                                    input.value = new Intl.NumberFormat('id-ID').format(data[
                                        bankId]);
                                    input.readOnly = true;
                                }
                                if (badge) {
                                    badge.classList.remove('hidden');
                                    badge.classList.add('flex');
                                }
                                if (wrapper) {
                                    wrapper.classList.add('opacity-60');
                                }
                            });
                        }
                        updateSubmitButton();
                    })
                    .catch(err => {
                        console.error('Gagal memuat data saldo awal:', err);
                        updateSubmitButton();
                    })
                    .finally(() => {
                        document.body.style.cursor = 'default';
                    });
            });

            // Handle Submit Form
            document.getElementById('formSaldoAwal').addEventListener('submit', function(e) {
                const btn = this.querySelector('button[type="submit"]');

                if (btn.disabled || btn.dataset.submitting === 'true') {
                    e.preventDefault();
                    return;
                }
                btn.dataset.submitting = 'true';

                // Hapus format titik sebelum disimpan
                document.querySelectorAll('.currency-input').forEach(function(input) {
                    input.value = input.value.replace(/\D/g, '');
                });

                btn.disabled = true;
                btn.classList.add('opacity-70', 'cursor-not-allowed');
                btn.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                `;
            });
        });
    </script>
@endsection
