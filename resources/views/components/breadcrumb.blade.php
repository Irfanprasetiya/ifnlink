@php
    use Illuminate\Support\Facades\Request;
    use App\Models\Cabang;
    use App\Models\User;
    use App\Models\Bank;
    use App\Models\JenisTransaksi;

    $segments = Request::segments();
@endphp

<nav class="mt-4 max-sm:mt-0 bg-gray-100 rounded p-2 text-gray-600 select-none cursor-default text-sm"
    aria-label="breadcrumb">
    <ol class="list-reset flex">
        <li><a href="{{ route('main') }}" class="text-blue-600 hover:underline">Home</a></li>

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
                            $label = $model?->nama_jenis_transaksi ?? "ID $segment";
                            break;
                        default:
                            $label = "ID $segment";
                    }
                }
            @endphp

            @if (!is_numeric($segment))
                {{-- tampilkan breadcrumb --}}

                <li class="mx-2">/</li>
                <li>
                    @if ($isLast)
                        <span class="text-gray-500">{{ $label }}</span>
                    @else
                        <a href="{{ $url }}" class="text-blue-600 hover:underline">{{ $label }}</a>
                    @endif
                </li>
            @endif


        @endforeach
    </ol>
</nav>