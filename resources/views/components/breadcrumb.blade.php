@php
    use Illuminate\Support\Facades\Request;
    use App\Models\Cabang;
    use App\Models\User;
    use App\Models\Bank;
    use App\Models\JenisTransaksi;

    $segments = Request::segments();
@endphp

<!-- Tambahkan class untuk hide scrollbar agar rapi saat di-swipe di HP -->
<nav class="mt-2 text-[11px] sm:text-xs md:text-sm font-medium select-none cursor-default w-full overflow-x-auto whitespace-nowrap [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]"
    aria-label="breadcrumb">

    <ol class="flex items-center min-w-max">
        <!-- Home (Icon Only di Mobile, Teks di PC) -->
        <li class="flex items-center">
            <a href="{{ route('main') }}"
                class="text-slate-400 hover:text-blue-600 transition-colors flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="hidden sm:inline-block">Home</span>
            </a>
        </li>

        @foreach ($segments as $index => $segment)
            @php
                $isLast = $loop->last;
                $prevSegment = $segments[$index - 1] ?? null;
                $label = ucwords(str_replace(['-', '_'], ' ', $segment));
                $url = url(implode('/', array_slice($segments, 0, $index + 1)));

                if (is_numeric($segment)) {
                    switch ($prevSegment) {
                        case 'cabang':
                            $model = Cabang::find($segment);
                            $label = $model?->nama_cabang ?? "ID $segment";
                            break;
                        case 'user':
                            $model = User::find($segment);
                            $label = $model?->name ?? "ID $segment";
                            break;
                        case 'bank':
                            $model = Bank::find($segment);
                            $label = $model?->nama_bank ?? "ID $segment";
                            break;
                        case 'jenis_transaksi':
                            $model = JenisTransaksi::find($segment);
                            $label = $model?->nama_transaksi ?? "ID $segment";
                            break;
                        default:
                            $label = "ID $segment";
                    }
                }
            @endphp

            <!-- Separator Chevron -->
            <li class="mx-1.5 sm:mx-2 text-slate-300 flex items-center">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    stroke-width="3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                </svg>
            </li>

            <!-- Segment -->
            <li class="flex items-center">
                @if ($isLast)
                    <!-- Teks Bold untuk halaman aktif saat ini -->
                    <span
                        class="text-slate-800 font-bold truncate max-w-[150px] sm:max-w-none">{{ $label }}</span>
                @else
                    <!-- Link biru untuk halaman sebelumnya -->
                    <a href="{{ $url }}"
                        class="text-blue-600 hover:text-blue-700 hover:underline transition-colors truncate max-w-[120px] sm:max-w-none">{{ $label }}</a>
                @endif
            </li>
        @endforeach
    </ol>
</nav>
