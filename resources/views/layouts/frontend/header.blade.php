<!-- Header Box -->
<section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-7 mt-20 mb-8">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <p class="text-sm md:text-base text-gray-500 mb-1">Selamat datang kembali</p>
            <h2 class="text-xl md:text-2xl lg:text-3xl font-semibold text-gray-800 select-none cursor-default">
                @auth
                    {{ Auth::user()->cabang->nama_cabang }}
                @endauth
            </h2>
        </div>

        <div
            class="flex items-center gap-2 text-sm md:text-base text-gray-500 bg-gray-50 rounded-lg px-3 py-2 md:px-4 md:py-2.5 w-fit">
            <span class="material-symbols-outlined text-base md:text-lg">calendar_today</span>
            {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>
    </div>

    <!-- breadcrumb -->
    <x-breadcrumb />
</section>
