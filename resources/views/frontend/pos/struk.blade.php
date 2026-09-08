<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk #{{ $penjualan->kode_transaksi ?? $penjualan->id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            .struk-container {
                box-shadow: none !important;
                border: none !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center p-4">
    <div
        class="struk-container bg-white rounded-2xl shadow-lg border border-slate-200 max-w-xs w-full p-6 font-mono text-sm">

        {{-- Header --}}
        <div class="text-center mb-4">
            <h1 class="font-bold text-lg">{{ $penjualan->tenant->nama_toko ?? 'OMZETLY.ID' }}</h1>
            <p class="text-xs">{{ $penjualan->cabang->nama_cabang ?? 'Cabang' }}</p>
            <p class="text-xs text-slate-500">{{ $penjualan->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Info Transaksi --}}
        <div class="space-y-1 text-xs mb-4">
            <div class="flex justify-between">
                <span>No. Transaksi</span>
                <span class="font-bold">{{ $penjualan->kode_transaksi ?? 'TRX-' . $penjualan->id }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir</span>
                <span>{{ $penjualan->user->name ?? '-' }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Items --}}
        <div class="space-y-2 mb-4">
            @foreach ($penjualan->details as $d)
                <div class="flex justify-between text-xs">
                    <div>
                        <p class="font-bold">{{ $d->voucher->nama_produk }}</p>
                        <p class="text-slate-500">{{ $d->qty }} x Rp
                            {{ number_format($d->harga_satuan, 0, ',', '.') }}</p>
                    </div>
                    <span class="font-bold">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</span>
                </div>
            @endforeach
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Total --}}
        <div class="space-y-1 text-sm mb-4">
            <div class="flex justify-between">
                <span>Total</span>
                <span>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
            </div>
            @if ($penjualan->diskon > 0)
                <div class="flex justify-between text-rose-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                </div>
            @endif
            <div class="flex justify-between font-bold text-base border-t border-slate-200 pt-2">
                <span>Grand Total</span>
                <span>Rp {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Bayar</span>
                <span>Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold">
                <span>Kembalian</span>
                <span>Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-slate-300 my-3"></div>

        {{-- Footer --}}
        <div class="text-center text-xs">
            <p class="font-bold">Terima Kasih!</p>
            <p class="text-slate-500">Barang yang sudah dibeli tidak dapat dikembalikan</p>
        </div>

        {{-- ✅ Tombol Bluetooth & Print --}}
        <div class="no-print mt-4 space-y-2">
            <button onclick="connectBluetooth()" id="btn-bluetooth"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-lg text-sm flex items-center justify-center gap-2">
                <svg id="bt-spinner" class="hidden animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span id="bt-text">Connect Bluetooth</span>
            </button>

            <button onclick="printStruk()" id="btn-print-bt" disabled
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                Cetak via Bluetooth
            </button>

            <button onclick="window.print()"
                class="w-full bg-slate-600 hover:bg-slate-700 text-white font-bold py-2.5 rounded-lg text-sm">
                Print Biasa
            </button>
        </div>
    </div>

    <script>
        let bluetoothDevice = null;
        let bluetoothCharacteristic = null;

        // ✅ Connect Bluetooth
        async function connectBluetooth() {
            const btn = document.getElementById('btn-bluetooth');
            const spinner = document.getElementById('bt-spinner');
            const text = document.getElementById('bt-text');

            try {
                if (!navigator.bluetooth) {
                    alert('Browser tidak support Bluetooth. Gunakan Chrome Android.');
                    return;
                }

                btn.disabled = true;
                spinner.classList.remove('hidden');
                text.textContent = 'Menghubungkan...';

                bluetoothDevice = await navigator.bluetooth.requestDevice({
                    acceptAllDevices: true,
                    optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
                });

                const server = await bluetoothDevice.gatt.connect();
                const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
                bluetoothCharacteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');

                text.textContent = '✓ Terhubung';
                document.getElementById('btn-print-bt').disabled = false;

                alert('Bluetooth berhasil terhubung!');

            } catch (error) {
                console.error('Bluetooth error:', error);
                text.textContent = 'Connect Bluetooth';
                alert('Gagal terhubung: ' + error.message);
            } finally {
                btn.disabled = false;
                spinner.classList.add('hidden');
            }
        }

        // ✅ Print via Bluetooth dengan chunk & font besar
        async function printStruk() {
            if (!bluetoothCharacteristic) {
                alert('Hubungkan Bluetooth dulu!');
                return;
            }

            const struk = `
{{ $penjualan->tenant->nama_toko ?? 'OMZETLY.ID' }}
{{ $penjualan->cabang->nama_cabang ?? '' }}
{{ $penjualan->created_at->format('d/m/Y H:i') }}
----------------------------
No: {{ $penjualan->kode_transaksi }}
Kasir: {{ $penjualan->user->name ?? '-' }}
----------------------------
@foreach ($penjualan->details as $d)
{{ $d->voucher->nama_produk }}
{{ $d->qty }} x Rp {{ number_format($d->harga_satuan, 0, ',', '.') }} = Rp {{ number_format($d->subtotal, 0, ',', '.') }}
@endforeach
----------------------------
@if ($penjualan->diskon > 0)
Diskon: -Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}
@endif
TOTAL: Rp {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}
BAYAR: Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}
KEMBALI: Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}
----------------------------
Terima Kasih!
`;

            try {
                const encoder = new TextEncoder();

                // ESC/POS commands
                const init = new Uint8Array([0x1B, 0x40]); // Initialize
                const center = new Uint8Array([0x1B, 0x61, 0x01]); // Center
                const left = new Uint8Array([0x1B, 0x61, 0x00]); // Left
                const boldOn = new Uint8Array([0x1B, 0x45, 0x01]); // Bold on
                const boldOff = new Uint8Array([0x1B, 0x45, 0x00]); // Bold off
                const doubleHeight = new Uint8Array([0x1D, 0x21, 0x11]); // Double height
                const normalSize = new Uint8Array([0x1D, 0x21, 0x00]); // Normal size
                const feed = new Uint8Array([0x1B, 0x64, 0x04]); // Feed 4 lines
                const cut = new Uint8Array([0x1D, 0x56, 0x42, 0x00]); // Partial cut

                // ✅ Init printer
                await bluetoothCharacteristic.writeValue(init);
                await new Promise(r => setTimeout(r, 50));

                // ✅ Header - Double height & bold
                await bluetoothCharacteristic.writeValue(center);
                await bluetoothCharacteristic.writeValue(doubleHeight);
                await bluetoothCharacteristic.writeValue(boldOn);

                const header = `{{ $penjualan->tenant->nama_toko ?? 'OMZETLY.ID' }}\n{{ $penjualan->cabang->nama_cabang ?? '' }}\n{{ $penjualan->created_at->format('d/m/Y H:i') }}\n`;
                await sendChunk(encoder.encode(header));

                await bluetoothCharacteristic.writeValue(boldOff);
                await bluetoothCharacteristic.writeValue(normalSize);
                await bluetoothCharacteristic.writeValue(left);

                // ✅ Body
                const body = `----------------------------\nNo: {{ $penjualan->kode_transaksi }}\nKasir: {{ $penjualan->user->name ?? '-' }}\n----------------------------\n@foreach ($penjualan->details as $d){{ $d->voucher->nama_produk }}\n{{ $d->qty }} x Rp {{ number_format($d->harga_satuan, 0, ',', '.') }} = Rp {{ number_format($d->subtotal, 0, ',', '.') }}\n@endforeach----------------------------\n@if ($penjualan->diskon > 0)Diskon: -Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}\n@endifTOTAL: Rp {{ number_format($penjualan->total_setelah_diskon, 0, ',', '.') }}\nBAYAR: Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}\nKEMBALI: Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}\n`;
                await sendChunk(encoder.encode(body));

                // ✅ Footer - Bold
                await bluetoothCharacteristic.writeValue(boldOn);
                await sendChunk(encoder.encode('----------------------------\n'));
                await bluetoothCharacteristic.writeValue(boldOff);

                // ✅ Feed & Cut
                await bluetoothCharacteristic.writeValue(feed);
                await bluetoothCharacteristic.writeValue(cut);

                alert('Struk berhasil dicetak!');

            } catch (error) {
                console.error('Print error:', error);
                alert('Gagal cetak: ' + error.message);
            }
        }

        // ✅ Helper: Kirim per chunk 32 byte
        async function sendChunk(data) {
            for (let i = 0; i < data.length; i += 32) {
                const chunk = data.slice(i, i + 32);
                await bluetoothCharacteristic.writeValue(chunk);
                await new Promise(r => setTimeout(r, 20)); // Jeda 20ms
            }
        }
    </script>
</body>

</html>