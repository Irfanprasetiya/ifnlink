@extends('layouts.guest')

@section('title', 'Daftar Agen | Omzetly.id')

@section('container')
    <section class="flex items-center justify-center min-h-screen px-6 py-10" x-data="{
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
        <div class="w-full max-w-lg bg-white/80 backdrop-blur-md rounded-2xl shadow-lg dark:bg-gray-800/80 p-8">
            <div class="flex flex-col items-center mb-6">
                <img class="h-24 mb-3" src="{{ asset('assets/images/omzetly.png') }}" alt="logo">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Daftar Agen</h1>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Mulai kelola bisnis Toko Anda</p>
            </div>

            {{-- Progress indicator --}}
            <div class="flex items-center gap-2 mb-6">
                <div class="flex-1 h-1.5 rounded-full" :class="step >= 1 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                </div>
                <div class="flex-1 h-1.5 rounded-full" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-600'">
                </div>
            </div>

            @if (session('error'))
                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-700 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('info'))
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-700 dark:text-blue-400">
                    {{ session('info') }}
                </div>
            @endif

            @php
                $pendingTenantId = session('pending_tenant_id');
                $pendingTenant = $pendingTenantId ? \App\Models\Tenant::find($pendingTenantId) : null;
            @endphp

            @if ($pendingTenant && $pendingTenant->status_langganan === 'pending')
                <div class="mb-6 bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">
                    <p class="text-sm font-medium mb-2">
                        Pendaftaran untuk <strong>{{ $pendingTenant->nama_toko }}</strong> sudah berhasil.
                        Silakan selesaikan pembayaran untuk mengaktifkan akun.
                    </p>
                    <a href="{{ route('checkout', ['plan' => $pendingTenant->plan_id]) }}"
                        class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700 transition">
                        Lanjutkan Pembayaran
                    </a>
                </div>
            @endif

            <form method="POST" action="{{ route('agen.store') }}" class="space-y-5" id="registerForm">
                @csrf
                <input type="hidden" name="plan_id" x-model="plan_id">

                {{-- STEP 1 --}}
                <div x-show="step === 1" id="step1" class="space-y-5">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="nama_toko"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Toko</label>
                            <input type="text" name="nama_toko" id="nama_toko" value="{{ old('nama_toko') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="Contoh: Toko Berkah" @input="hasErrors = false" required>
                            <x-input-error :messages="$errors->get('nama_toko')" class="mt-1" />
                        </div>
                        <div>
                            <label for="nama_pemilik"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Nama Pemilik</label>
                            <input type="text" name="nama_pemilik" id="nama_pemilik" value="{{ old('nama_pemilik') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="Nama lengkap pemilik" @input="hasErrors = false" required>
                            <x-input-error :messages="$errors->get('nama_pemilik')" class="mt-1" />
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="no_hp"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">No.
                                Handphone</label>
                            <input type="text" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="08123xxx" @input="hasErrors = false" required>
                            <x-input-error :messages="$errors->get('no_hp')" class="mt-1" />
                        </div>
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Email Bisnis</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}"
                                class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                                placeholder="toko@email.com" @input="hasErrors = false" required>
                            <x-input-error :messages="$errors->get('email')" class="mt-1" />
                        </div>
                    </div>

                    <hr class="border-gray-200 dark:border-gray-700">

                    <div>
                        <label for="username"
                            class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                        <input type="text" name="username" id="username" value="{{ old('username') }}"
                            @input="hasErrors = false"
                            class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                            placeholder="Buat username unik" required>
                        <x-input-error :messages="$errors->get('username')" class="mt-1" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" id="password" minlength="6"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition pr-10"
                                    placeholder="••••••••" required @input="passwordMatch = null; hasErrors = false">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
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
                            <x-input-error :messages="$errors->get('password')" class="mt-1" />
                        </div>

                        <div>
                            <label for="password_confirmation"
                                class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi
                                Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation"
                                    id="password_confirmation" minlength="6"
                                    class="w-full px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition pr-10"
                                    placeholder="••••••••" required
                                    @input="passwordMatch = ($el.value === document.getElementById('password').value); hasErrors = false">
                                <button type="button" @click="show = !show"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600">
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
                            <p x-show="passwordMatch === true" class="text-xs text-green-600 mt-1">Password sesuai</p>
                            <p x-show="passwordMatch === false" class="text-xs text-red-600 mt-1">Password tidak sesuai
                            </p>
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                        </div>
                    </div>

                    <button type="button" @click="nextStep()"
                        class="w-full py-3 mt-2 text-white font-semibold bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition focus:ring-4 focus:ring-blue-300 outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                        :disabled="hasErrors">
                        Lanjut Pilih Paket
                    </button>
                    <p x-show="hasErrors" class="text-xs text-red-600 text-center mt-1">
                        Silakan perbaiki error di atas sebelum melanjutkan.
                    </p>
                </div>

                {{-- STEP 2 --}}
                <div x-show="step === 2" x-cloak class="space-y-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Pilih Paket</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Bisa upgrade kapan saja setelah daftar</p>
                    </div>

                    <div class="space-y-3">
                        @forelse($plans as $plan)
                            <label class="block border rounded-xl p-4 cursor-pointer transition hover:border-blue-400"
                                :class="plan_id === '{{ $plan->id }}' ?
                                    'border-blue-600 ring-1 ring-blue-600 bg-blue-50 dark:bg-blue-900/20' :
                                    'border-gray-300 dark:border-gray-600'">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="plan_radio" value="{{ $plan->id }}"
                                            x-model="plan_id"
                                            @change="selectPlan('{{ $plan->id }}', {{ (int) $plan->harga }}, '{{ $plan->nama_paket }}')"
                                            class="w-4 h-4 text-blue-600">
                                        <div>
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $plan->nama_paket }}
                                                @if ($plan->harga == 0)
                                                    <span
                                                        class="text-xs bg-green-100 text-green-800 px-2 py-0.5 rounded-full ml-2">Gratis</span>
                                                @elseif($plan->harga == $plans->where('harga', '>', 0)->max('harga'))
                                                    <span
                                                        class="text-xs bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full ml-2">Populer</span>
                                                @endif
                                            </p>
                                            @if ($plan->max_user)
                                                <p class="text-xs text-gray-400 mt-1">👥 Maks. {{ $plan->max_user }} User
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        @if ($plan->harga == 0)
                                            <p class="font-semibold text-green-600 dark:text-green-400 text-lg">Gratis</p>
                                        @else
                                            <p class="font-semibold text-gray-900 dark:text-white text-lg">Rp
                                                {{ number_format($plan->harga, 0, ',', '.') }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">/bulan</p>
                                        @endif
                                    </div>
                                </div>
                            </label>
                        @empty
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">⚠️ Belum ada paket tersedia.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4" x-show="plan_id">
                        <p class="text-sm text-gray-600 dark:text-gray-300">Paket dipilih: <span class="font-semibold"
                                x-text="plan_nama"></span></p>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            Harga: <span class="font-semibold">
                                <span x-show="plan_harga == 0" class="text-green-600">Gratis</span>
                                <span x-show="plan_harga > 0"
                                    x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(plan_harga) + '/bulan'"></span>
                            </span>
                        </p>
                    </div>

                    <div class="flex gap-3">
                        <button type="button" @click="step = 1"
                            class="flex-1 py-3 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 font-semibold rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            ← Kembali
                        </button>
                        <button type="submit" id="btnSubmitRegister" :disabled="!plan_id"
                            class="flex-1 py-3 text-white font-semibold bg-blue-600 hover:bg-blue-700 rounded-lg shadow-md transition focus:ring-4 focus:ring-blue-300 outline-none disabled:opacity-50 disabled:cursor-not-allowed">
                            <span x-show="plan_harga == 0">Daftar Sekarang (Gratis)</span>
                            <span x-show="plan_harga > 0">Lanjut ke Pembayaran</span>
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-6 text-center text-sm">
                <span class="text-gray-500 dark:text-gray-400">Sudah punya akun?</span>
                <a href="{{ route('login') }}" class="text-blue-600 hover:underline font-semibold ml-1">Masuk di sini</a>
            </div>
            <div class="mt-4 flex justify-center items-center">
                <a href="/"
                    class="flex items-center text-sm font-medium text-blue-600 hover:text-blue-700 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Kembali ke Beranda
                </a>
            </div>
            <p class="text-center text-xs text-gray-400 mt-8">© {{ date('Y') }} Omzetly.id. All rights reserved.</p>
        </div>
    </section>

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
