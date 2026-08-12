@extends('layouts.app')

@section('title', 'Upgrade Paket')

@section('container')
    <div class="px-3 sm:px-6 lg:px-8 py-4 sm:py-6 max-w-4xl mx-auto">
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mb-2">Upgrade Paket</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Pilih paket PRO untuk menikmati semua fitur.</p>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach ($plans as $plan)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-100 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">{{ $plan->nama_paket }}</h2>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-3">
                        Rp {{ number_format($plan->harga, 0, ',', '.') }}
                        <span class="text-sm font-normal text-gray-500">/bulan</span>
                    </p>

                    <ul class="mt-5 space-y-2">
                        @if (is_array($plan->fitur))
                            @foreach ($plan->fitur as $fitur)
                                <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                    ✓ {{ $fitur }}
                                </li>
                            @endforeach
                        @endif
                    </ul>

                    <form action="{{ route('status.upgrade-proses') }}" method="POST" class="mt-6">
                        @csrf
                        <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                        <button type="submit"
                            class="w-full py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition">
                            Pilih Paket & Bayar
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>
@endsection
