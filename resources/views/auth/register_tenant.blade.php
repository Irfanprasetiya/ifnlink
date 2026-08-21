@extends('layouts.guest')

@section('title', 'Daftar Agen | Omzetly.id')

@section('container')
    {{-- Background Ornaments --}}
    <div class="fixed top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
        <div
            class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] rounded-full bg-blue-400/20 dark:bg-blue-900/20 blur-[100px]">
        </div>
        <div
            class="absolute bottom-[-10%] right-[-10%] w-[40%] h-[40%] rounded-full bg-indigo-400/20 dark:bg-indigo-900/20 blur-[100px]">
        </div>
    </div>

    <section class="flex items-center justify-center min-h-screen px-4 sm:px-6 py-10 relative z-10" x-data="{
        step: 1,
        plan_id: '',
        plan_harga: 0,
        plan_nama: '',
        passwordMatch: null,
        hasErrors: @json($errors->any()),
    
        nextStep() {
            if (this.hasErrors) return;
    
            const inputs = document.querySelectorAll('#step1 [required]');
            for (const input of inputs) {
                if (!input.value) {
                    input.reportValidity();
                    return;
                }
            }
            const pw = document.getElementById('password').value;
            const confirm = document.getElementById('password_confirmation').value;
            if (pw !== confirm) {
                this.passwordMatch = false;
                return;
            }
            this.passwordMatch = true;
            this.step = 2;
        },
    
        selectPlan(id, harga, nama) {
            this.plan_id = id;
            this.plan_harga = harga;
            this.plan_nama = nama;
        }
    }">
        <div
            class="w-full max-w-xl bg-white/80 dark:bg-gray-800/80 backdrop-blur-xl rounded-3xl shadow-2xl p-6 sm:p-8 border border-white/50 dark:border-gray-700/50 animate-in fade-in zoom-in-95 duration-500">

            {{-- Header Logo --}}
            <div class="flex flex-col items-center mb-6 sm:mb-8 text-center">
                <div class="relative group cursor-pointer mb-3">
                    <div
                        class="absolute inset-0 bg-blue-100 dark:bg-blue-900/50 rounded-full blur-xl opacity-50 group-hover:opacity-100 transition-opacity duration-300">
                    </div>
                    <img class="h-20 sm:h-24 relative z-10 drop-shadow-lg transition-transform duration-300 group-hover:scale-105"
                        src="{{ asset('assets/images/logo/favicon.png') }}" alt="logo">
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white tracking-tight">Daftar Agen
                </h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base mt-1 font-medium">Mulai kelola bisnis Toko
                    Anda hari ini.</p>
            </div>

            {{-- Progress indicator --}}
            <div class="flex items-center gap-2.5 mb-6 sm:mb-8">
                <div class="flex-1 h-2 rounded-full transition-colors duration-300"
                    :class="step >= 1 ? 'bg-blue-600 shadow-sm shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700'"></div>
                <div class="flex-1 h-2 rounded-full transition-colors duration-300"
                    :class="step >= 2 ? 'bg-blue-600 shadow-sm shadow-blue-500/30' : 'bg-gray-200 dark:bg-gray-700'"></div>
            </div>

            {{-- Alerts --}}
            <div class="space-y-3 mb-6">
                @if (session('error'))
                    <div
                        class="bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-400 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm text-[11px] sm:text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                @if (session('info'))
                    <div
                        class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-400 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm text-[11px] sm:text-sm font-medium">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif
            </div>

            @php
                $pendingTenantId = session('pending_tenant_id');
                $pendingTenant = $pendingTenantId ? \App\Models\Tenant::find($pendingTenantId) : null;
            @endphp

            @if ($pendingTenant && $pendingTenant->status_langganan === 'pending')
                <div
                    class="mb-6 bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-300 px-5 py-4 rounded-2xl shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-sm font-medium mb-3 leading-relaxed">
                            Pendaftaran untuk <strong>{{ $pendingTenant->nama_toko }}</strong> sudah berhasil. Silakan
                            selesaikan pembayaran untuk mengaktifkan akun.
                        </p>
                        <a href="{{ route('checkout', ['plan' => $pendingTenant->plan_id]) }}"
                            class="inline-flex items-center justify-center w-full sm:w-auto bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-700 transition active:scale-95 shadow-sm">
                            💳 Lanjutkan Pembayaran
                        </a>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('agen.store') }}" class="space-y-5" id="registerForm">
                @csrf
                <input type="hidden" name="plan_id" x-model="plan_id">

                {{-- STEP 1 --}}
                <div x-show="step === 1" id="step1" class="space-y-4 sm:space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="nama_toko"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Nama
                                Toko</label>
                            <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}" required
                                @input="hasErrors = false"
                                class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                                placeholder="Contoh: Toko Berkah">
                            <x-input-error :messages="$errors->get('nama_toko')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                        <div>
                            <label for="nama_pemilik"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Nama
                                Pemilik</label>
                            <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik') }}"
                                required @input="hasErrors = false"
                                class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                                placeholder="Nama lengkap pemilik">
                            <x-input-error :messages="$errors->get('nama_pemilik')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="no_hp"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">No.
                                Handphone</label>
                            <input type="text" inputmode="numeric" name="no_hp" id="no_hp"
                                value="{{ old('no_hp') }}" required @input="hasErrors = false"
                                class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                                placeholder="08123xxxx">
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Email
                                Bisnis</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                @input="hasErrors = false"
                                class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                                placeholder="toko@email.com">
                            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                    </div>

                    <hr class="border-gray-100 dark:border-gray-700/50 my-2">

                    <div>
                        <label for="username"
                            class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Username
                            Login</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}" required
                            @input="hasErrors = false"
                            class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all"
                            placeholder="Buat username unik">
                        <x-input-error :messages="$errors->get('username')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-5">
                        <div>
                            <label for="password"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" minlength="6"
                                    required @input="passwordMatch = null; hasErrors = false"
                                    class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all pr-12"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.617-3.675m6.758-1.158A9.96 9.96 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.96 9.96 0 01-2.617 3.675" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block mb-1.5 text-[11px] sm:text-xs font-bold uppercase tracking-wider text-gray-600 dark:text-gray-400">Konfirmasi
                                Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" minlength="6" required
                                    @input="passwordMatch = ($el.value === document.getElementById('password').value); hasErrors = false"
                                    class="w-full px-4 py-3.5 sm:py-3 rounded-xl border border-gray-200 dark:border-gray-600 bg-gray-50 dark:bg-gray-700/50 text-gray-900 dark:text-white text-base sm:text-sm focus:bg-white dark:focus:bg-gray-800 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-600 outline-none transition-all pr-12"
                                    placeholder="••••••••">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg x-show="show" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.05 10.05 0 012.617-3.675m6.758-1.158A9.96 9.96 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.96 9.96 0 01-2.617 3.675" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                            <p x-show="passwordMatch === true"
                                class="text-[10px] sm:text-xs text-emerald-600 mt-1.5 font-medium">✅ Password sesuai</p>
                            <p x-show="passwordMatch === false"
                                class="text-[10px] sm:text-xs text-rose-600 mt-1.5 font-medium">❌ Password tidak sesuai</p>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-[10px] sm:text-xs text-rose-500" />
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="button" @click="nextStep()" :disabled="hasErrors"
                            class="w-full py-3.5 mt-2 text-white font-bold bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all active:scale-95 outline-none disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                            Lanjut Pilih Paket
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                        <p x-show="hasErrors" class="text-[11px] sm:text-xs text-rose-600 text-center mt-2 font-medium">
                            ⚠️ Silakan perbaiki error di atas sebelum melanjutkan.
                        </p>
                    </div>
                </div>

                {{-- STEP 2 --}}
                <div x-show="step === 2" x-cloak class="space-y-5">
                    <div class="text-center sm:text-left mb-2">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-white mb-1">Pilih Paket Bisnis</h2>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Anda dapat melakukan *upgrade* kapan
                            saja setelah mendaftar.</p>
                    </div>

                    <div class="space-y-3">
                        @forelse($plans as $plan)
                            <label
                                class="block border rounded-2xl p-4 sm:p-5 cursor-pointer transition-all duration-200 hover:border-blue-400 shadow-sm"
                                :class="plan_id === '{{ $plan->id }}' ?
                                    'border-blue-600 ring-2 ring-blue-600/20 bg-blue-50/50 dark:bg-blue-900/20' :
                                    'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800'">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center gap-3 sm:gap-4">
                                        <input type="radio" name="plan_radio" value="{{ $plan->id }}"
                                            x-model="plan_id"
                                            @change="selectPlan('{{ $plan->id }}', {{ (int) $plan->harga }}, '{{ $plan->nama_paket }}')"
                                            class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 focus:ring-blue-500 border-gray-300">
                                        <div>
                                            <p
                                                class="font-bold text-gray-900 dark:text-white text-sm sm:text-base flex items-center flex-wrap gap-2">
                                                {{ $plan->nama_paket }}
                                                @if ($plan->harga == 0)
                                                    <span
                                                        class="text-[10px] bg-emerald-100 text-emerald-700 border border-emerald-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Gratis</span>
                                                @elseif($plan->harga == $plans->where('harga', '>', 0)->max('harga'))
                                                    <span
                                                        class="text-[10px] bg-blue-100 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-md uppercase tracking-wider">Populer</span>
                                                @endif
                                            </p>
                                            @if ($plan->max_user)
                                                <p class="text-[11px] sm:text-xs text-gray-500 mt-1 font-medium">👥
                                                    Maksimal {{ $plan->max_user }} Akses User</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0">
                                        @if ($plan->harga == 0)
                                            <p
                                                class="font-extrabold text-emerald-600 dark:text-emerald-400 text-base sm:text-lg">
                                                Gratis</p>
                                        @else
                                            <p class="font-extrabold text-gray-900 dark:text-white text-base sm:text-lg">Rp
                                                {{ number_format($plan->harga, 0, ',', '.') }}</p>
                                            <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400 font-medium">
                                                / bulan</p>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div
                                class="text-center py-8 bg-gray-50 dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700">
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-medium">⚠️ Belum ada paket tersedia
                                    saat ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-100 dark:border-gray-600 shadow-inner"
                        x-show="plan_id" x-transition>
                        <div class="flex justify-between items-center text-xs sm:text-sm">
                            <span class="text-gray-600 dark:text-gray-300">Paket yang dipilih:</span>
                            <span class="font-bold text-gray-900 dark:text-white" x-text="plan_nama"></span>
                        </div>
                        <div class="flex justify-between items-center text-xs sm:text-sm mt-1.5">
                            <span class="text-gray-600 dark:text-gray-300">Total Pembayaran:</span>
                            <span class="font-bold">
                                <span x-show="plan_harga == 0" class="text-emerald-600">Gratis 14 Hari</span>
                                <span x-show="plan_harga > 0" class="text-blue-600"
                                    x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(plan_harga) + ' / bln'"></span>
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" @click="step = 1"
                            class="w-1/3 py-3.5 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-bold text-sm rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition active:scale-95">
                            ← Kembali
                        </button>
                        <button type="submit" id="btnSubmitRegister" :disabled="!plan_id"
                            class="w-2/3 py-3.5 text-white font-bold text-sm bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all active:scale-95 outline-none disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                            <span x-show="plan_harga == 0">Daftar Sekarang</span>
                            <span x-show="plan_harga > 0">Lanjut Pembayaran</span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-8 text-center border-t border-gray-100 dark:border-gray-700/50 pt-6">
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 font-medium">
                    Sudah punya akun?
                    <a href="{{ route('login') }}"
                        class="font-bold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 hover:underline transition-colors ml-1">
                        Masuk di sini
                    </a>
                </p>
            </div>

            <div class="mt-4 flex justify-center items-center">
                <a href="/"
                    class="inline-flex items-center text-xs sm:text-sm font-semibold text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors bg-gray-50 dark:bg-gray-800 px-4 py-2 rounded-lg border border-gray-100 dark:border-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>

            <p class="text-center text-[10px] sm:text-[11px] text-gray-400 mt-8 tracking-wide font-medium">
                © {{ date('Y') }} Omzetly.id. ALL RIGHTS RESERVED.
            </p>
        </div>
    </section>

    {{-- Script Loading UTUH TIDAK DIUBAH --}}
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('btnSubmitRegister');
            if (btn) {
                btn.disabled = true;
                btn.innerHTML =
                    '<span class="flex items-center justify-center"><svg class="animate-spin h-5 w-5 mr-2" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</span>';
            }
        });
    </script>
@endsection
