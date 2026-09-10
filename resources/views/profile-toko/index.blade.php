{{-- resources/views/profile-toko/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Profile Toko')

@section('container')
    <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6 pb-32 mt-4 sm:mt-7">

        {{-- Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-extrabold text-slate-800">Profile Toko</h1>
                    <p class="text-xs sm:text-sm text-slate-500">Kelola informasi toko Anda</p>
                </div>
            </div>
        </div>

        {{-- Alert --}}
        <div id="alertBox" class="hidden"></div>

        {{-- Form --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-4 sm:p-6">
            <form id="formProfile" onsubmit="submitProfile(event)">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    {{-- Nama Toko --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                            Nama Toko <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_toko" value="{{ $tenant->nama_toko }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:bg-white transition-all">
                    </div>

                    {{-- Nama Pemilik --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                            Nama Pemilik <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="nama_pemilik" value="{{ $tenant->nama_pemilik }}" required
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:bg-white transition-all">
                        <p class="text-[10px] text-slate-400 mt-1">* Akan tersinkron dengan nama akun Anda</p>
                    </div>

                    {{-- Email & No HP --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                Email <span class="text-rose-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ $tenant->email }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:bg-white transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                                No HP <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="no_hp" value="{{ $tenant->no_hp }}" required
                                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:bg-white transition-all">
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wide">
                            Alamat
                        </label>
                        <textarea name="alamat" rows="3" placeholder="Alamat lengkap toko"
                            class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-blue-500/30 focus:bg-white transition-all">{{ $tenant->alamat }}</textarea>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-end">
                    <button type="submit" id="btnSubmit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-bold transition-colors shadow-sm flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                        <svg id="spinner" class="hidden w-4 h-4 animate-spin" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        <span id="btnText">Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showAlert(type, message) {
            const box = document.getElementById('alertBox');
            const colors = type === 'success' ?
                'bg-emerald-50 border-emerald-200 text-emerald-800' :
                'bg-rose-50 border-rose-200 text-rose-800';
            box.className = `flex items-start gap-3 ${colors} border px-4 py-3.5 rounded-xl text-sm font-medium`;
            box.textContent = message;
            box.classList.remove('hidden');

            setTimeout(() => box.classList.add('hidden'), 4000);
        }

        function submitProfile(event) {
            event.preventDefault();

            const form = event.target;
            const btn = document.getElementById('btnSubmit');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');

            // Cegah double submit
            if (btn.disabled) return false;

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.textContent = 'Menyimpan...';

            const formData = new FormData(form);

            fetch('{{ route('profile-toko.update') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                    } else {
                        showAlert('error', data.message || 'Gagal menyimpan.');
                    }
                })
                .catch(err => {
                    showAlert('error', 'Terjadi kesalahan: ' + err.message);
                })
                .finally(() => {
                    btn.disabled = false;
                    spinner.classList.add('hidden');
                    btnText.textContent = 'Simpan Perubahan';
                });

            return false;
        }
    </script>
@endsection
