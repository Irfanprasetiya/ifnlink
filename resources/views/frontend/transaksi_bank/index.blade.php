@extends('layouts.frontend.app')

@section('container')
    <!-- Data Transaksi Section -->
    <section class="bg-white rounded-lg shadow-md p-6 mb-10 flex-grow">
        <div class="grid grid-cols-2 max-md:grid-cols-1">
            <h2 class="text-start text-lg font-semibold mb-6 select-none cursor-default">Transaksi Bank</h2>
        </div>

        <div class="relative overflow-x-auto mt-5">
            <table class="min-w-full text-sm text-left text-gray-700 bg-white rounded-lg shadow-md">
                <thead class="text-xs uppercase bg-gradient-to-r bg-[#2563eb] to-indigo-600 text-white">
                    <tr>
                        <th class="px-4 py-3">No</th>
                        <th class="px-4 py-3">Nama Bank</th>
                        <th class="px-4 py-3">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse ($data as $index => $item)
                        <tr class="hover:bg-gray-50 even:bg-gray-100">
                            <td class="px-4 py-2 text-sm text-gray-700">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-sm">
                                @if ($item['saldo'] > 0 && strtolower($item['nama']) !== 'kas')
                                    <a href="{{ route('transaksi_banks.detail', ['bank_id' => $item['id']]) }}"
                                        class="text-blue-600 hover:underline font-medium">
                                        {{ $item['nama'] }}
                                    </a>
                                @else
                                    <span class="text-gray-500">{{ $item['nama'] }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm text-left font-semibold">
                                {{ number_format($item['saldo'], 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-4 text-center text-gray-500">
                                Tidak ada data transaksi untuk hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>



            </table>

        </div>
    </section>
@endsection
