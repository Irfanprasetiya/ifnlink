@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="bg-white rounded-lg shadow-md p-6 mb-10">
        <h3 class="text-lg md:text-xl font-semibold mb-4 select-none cursor-default">
            Transaksi BriLink Hari Ini
        </h3>
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 gap-5 text-white font-medium text-center">

            {{-- Saldo Kas --}}
            <div class="rounded-lg shadow-md bg-green-700 p-5 flex flex-col justify-center">
                <span class="text-base md:text-lg mb-1">Kas</span>
                <span class="text-2xl md:text-3xl font-bold">{{ number_format($saldoAkhirKas, 0, ',', '.') }}</span>
            </div>

            {{-- Pengurangan Kas --}}
            <div class="rounded-lg shadow-md bg-red-600 p-5 flex flex-col justify-center">
                <span class="mb-1 flex items-center justify-center gap-1">
                    <span class="text-white text-base md:text-xl font-normal">Pengurangan</span> Kas
                </span>
                <span class="text-2xl md:text-3xl font-bold">{{ number_format($penguranganKas, 0, ',', '.') }}</span>
            </div>

            {{-- Penambahan Kas --}}
            <div class="rounded-lg shadow-md bg-green-600 p-5 flex flex-col justify-center">
                <span class="mb-1 flex items-center justify-center gap-1">
                    <span class="text-white text-base md:text-xl font-normal">Penambahan</span> Kas
                </span>
                <span class="text-2xl md:text-3xl font-bold">{{ number_format($tambahanKas, 0, ',', '.') }}</span>
            </div>

            {{-- Tarik Tunai --}}
            <div class="rounded-lg shadow-md bg-yellow-500 p-5 flex flex-col justify-center text-white">
                <span class="text-base md:text-lg mb-1">Tarik Tunai</span>
                <span class="text-2xl md:text-3xl font-bold">{{ number_format($totalTarikTunai, 0, ',', '.') }}</span>
            </div>

            {{-- Transfer --}}
            <div class="rounded-lg shadow-md bg-blue-600 p-5 flex flex-col justify-center">
                <span class="text-base md:text-lg mb-1">Transfer</span>
                <span class="text-2xl md:text-3xl font-bold">{{ number_format($totalTransfer, 0, ',', '.') }}</span>
            </div>

        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const btn = document.getElementById("transactionDropdownButton");
            if (btn) {
                btn.onclick = function() {
                    // aksi
                };
            }
        });
    </script>
@endsection
