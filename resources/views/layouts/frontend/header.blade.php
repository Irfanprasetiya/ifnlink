<!-- Header Box -->
<section
    class="bg-white rounded-2xl sm:rounded-3xl shadow-sm border border-slate-200 p-4 sm:p-5 md:p-7 mt-16 sm:mt-20 mb-5 sm:mb-8 relative overflow-hidden">
    <!-- Dekorasi Latar Belakang Halus (Optional) -->
    <div
        class="absolute top-0 right-0 w-32 h-32 bg-blue-50 rounded-full blur-2xl opacity-60 -mr-10 -mt-10 pointer-events-none">
    </div>

    <!-- Margin bottom diperkecil di HP (mb-3) -->
    <div class="relative z-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 sm:gap-4 mb-3 sm:mb-5">
        <div>
            <!-- Tambahkan hidden sm:flex agar basa-basi ini hilang di HP -->
            <p
                class="hidden sm:flex text-[11px] sm:text-sm md:text-base text-slate-500 mb-0.5 sm:mb-1 font-medium items-center gap-1.5">
                👋 Selamat datang kembali,
            </p>
            <h2
                class="text-lg sm:text-xl md:text-2xl lg:text-3xl font-extrabold text-slate-800 tracking-tight select-none cursor-default">
                @auth
                    {{ Auth::user()->cabang->nama_cabang }}
                @endauth
            </h2>
        </div>

        <!-- Tambahkan hidden sm:flex agar badge tanggal hilang di HP, tapi tetap muncul di PC -->
        <div
            class="hidden sm:flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-sm md:text-base font-bold text-slate-600 bg-slate-50 border border-slate-200 rounded-lg sm:rounded-xl px-3 py-2 md:px-4 md:py-2.5 w-fit shadow-sm">
            <span class="material-symbols-outlined text-base md:text-[20px] text-blue-600">calendar_today</span>
            <span>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <!-- breadcrumb -->
    <div class="relative z-10 border-t border-slate-100 pt-2.5 mt-1 sm:mt-0 sm:border-t-0 sm:pt-0">
        <x-breadcrumb />
    </div>
</section>
