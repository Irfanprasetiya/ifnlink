<?php

use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\PengeluaranKasController;
use App\Http\Controllers\AkunPengeluaranController;
use App\Http\Controllers\BankSaldoController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\JenisTransaksiController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\LaporanBankAdminController;
use App\Http\Controllers\LaporanBankController;
use App\Http\Controllers\TransaksiBankController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\detailKonterController;
use App\Http\Controllers\laporanKonterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ProdukKonterController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanSaldoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SaldoGudangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRegisterController;

// 🔒 Halaman Login (Guest Saja)
Route::middleware(['guest.redirect', 'prevent-back'])->group(function () {
    Route::get('/', fn() => view('auth.login'));
});

// Admin & super admin Only
Route::middleware(['auth', 'role:owner,super_admin,admin', 'prevent-back'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // transaksi bank (tambah saldo)
    Route::get('/trx-bank', [BankSaldoController::class, 'index'])->name('trx-bank.index');
    Route::post('/trx-bank', [BankSaldoController::class, 'store'])->name('trx-bank.store');
    Route::delete('/trx-bank/{bankSaldo}', [BankSaldoController::class, 'destroy'])->name('trx-bank.destroy');
    // Route::get('/trx-bank/users-by-cabang/{cabang_id}', [BankSaldoController::class, 'getUsersByCabang']);
    Route::get('/get-users-by-cabang/{cabang_id}', [BankSaldoController::class, 'getUsersByCabang'])->name('ajax.getUsersByCabang');

    // new trial
    Route::post(
        '/cek-saldo-awal-bank',
        [BankSaldoController::class, 'cekSaldoAwalBank']
    )->name('cek.saldo.awal.bank');


    Route::get('/get-users-by-cabang/{id}', [UserController::class, 'getByCabang']);

    // Manajemen Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/register', [UserRegisterController::class, 'store'])->name('users.register');
    Route::put('/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Manajemen Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('data_master.cabang.index');
    Route::post('/cabang', [CabangController::class, 'store'])->name('data_master.cabang.store');
    Route::put('/cabang/{id}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');

    // Manajemen Data Master
    Route::prefix('data_master')->name('data_master.')->group(function () {
        Route::resource('vouchers', VoucherController::class);
        Route::resource('kategoris', KategoriController::class)->only(['index', 'store', 'update', 'edit', 'destroy']);
        Route::resource('produk_konter', ProdukKonterController::class);
        Route::resource('jenis-transaksi', JenisTransaksiController::class);

        Route::get('daftar_bank', [BankController::class, 'index'])->name('daftar_bank.index');
        Route::post('daftar_bank', [BankController::class, 'store'])->name('daftar_bank.store');
        Route::put('daftar_bank/{id}', [BankController::class, 'update'])->name('daftar_bank.update');
        Route::delete('daftar_bank/{id}', [BankController::class, 'destroy'])->name('daftar_bank.destroy');

        // Akun Pengeluaran
        Route::controller(AkunPengeluaranController::class)->group(function () {

            Route::get('/akun-pengeluaran', 'index')
                ->name('akun_pengeluaran.index');
            Route::post('/akun-pengeluaran', 'store')
                ->name('akun_pengeluaran.store');

            Route::get('akun-pengeluaran/check/{nama}', function ($nama) {

                $cek = \App\Models\AkunPengeluaran::where('nama_akun', $nama)->exists();

                return response()->json([
                    'exists' => $cek
                ]);

            });

            Route::put('/akun-pengeluaran/{akun}', 'update')
                ->name('akun_pengeluaran.update');

            Route::delete('/akun-pengeluaran/{akun}', 'destroy')
                ->name('akun_pengeluaran.destroy');

        });
        // akun end
    });

    // pengeluaran kas
    Route::get(
        '/pengeluaran',
        [PengeluaranKasController::class, 'index']
    )
        ->name('pengeluaran.index');

    Route::post(
        '/pengeluaran',
        [PengeluaranKasController::class, 'store']
    )
        ->name('pengeluaran.store');

    Route::delete(
        '/pengeluaran/{id}',
        [PengeluaranKasController::class, 'destroy']
    )
        ->name('pengeluaran.destroy');

    Route::put(
        '/pengeluaran/{id}',
        [PengeluaranKasController::class, 'update']
    )->name('pengeluaran.update');
    // end

    // Laporan Saldo
    Route::get(
        '/laporan-saldo',
        [LaporanSaldoController::class, 'index']
    )
        ->name('laporan_saldo.index');
    // end

    // Manajemen Barang Masuk
    Route::get('/barang_masuk', [BarangMasukController::class, 'index'])->name('barang_masuk.index');
    Route::resource('barang_masuk', BarangMasukController::class)->except(['show']);

    // laporan bank halaman admin
    Route::get('/laporan-bank-admin', [LaporanBankAdminController::class, 'index'])->name('laporan-bank.admin.index');
    Route::get('/laporan_bank/rekap', [LaporanBankAdminController::class, 'rekap'])->name('laporan_bank.rekap');
    Route::get('/laporan-bank-admin/get-users-by-cabang/{cabangId}', [LaporanBankAdminController::class, 'getUsersByCabang']);
    Route::put('/laporan-bank/{id}', [LaporanBankAdminController::class, 'update'])->name('laporan-bank.update');
    Route::delete('/laporan-bank/{id}', [LaporanBankAdminController::class, 'destroy'])->name('laporan-bank.destroy');

    // Laba Rugi
    Route::get('/laba-rugi', [LabaRugiController::class, 'index'])->name('laba_rugi.index');

    // Saldo gudang
    Route::get('/saldo-gudang', [SaldoGudangController::class, 'index'])
        ->name('saldo_gudang.index');




});

/// 👤 User Only
Route::middleware(['auth', 'role:user', 'prevent-back'])->group(function () {
    Route::get('/main', [LandingPageController::class, 'index'])->name('main');

    // Halaman utama transaksi
    Route::get('/transaksi-konter', [MainPageController::class, 'index'])->name('vouchers');

    // Detail produk konter
    Route::get('/transaksi-konter/{id}', [detailKonterController::class, 'show'])->name('transaksi.konter.detail');

    // Halaman detail (jika beda dari transaksi-konter)
    Route::get('/detail', [detailKonterController::class, 'index'])->name('detail');
    Route::get('/detail/{id}', [detailKonterController::class, 'show'])->name('detail.show');

    // Halaman laporan penjualan
    Route::get('/laporanKonter', [laporanKonterController::class, 'index'])->name('laporan_konter');
    Route::get('/laporanKonter', [laporanKonterController::class, 'index'])->name('laporan_konter');

    // Submit penjualan (POST)
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');



    // Halaman utama
    Route::get('/transaksi-bank', [TransaksiBankController::class, 'index'])->name('transaksi-bank');

    // Alias untuk route index (optional, jika redirect pakai transaksi_banks.index)
    Route::get('/transaksi-bank/index', [TransaksiBankController::class, 'index'])->name('transaksi_banks.index');

    // Simpan data
    Route::post('/transaksi-bank', [TransaksiBankController::class, 'store'])->name('transaksi_banks.store');

    // Detail per bank
    Route::get('/transaksi-bank/detail/{bank_id}', [TransaksiBankController::class, 'detail'])->name('transaksi_banks.detail');



    // laporan bank
    Route::get('/laporanBank', [LaporanBankController::class, 'index'])->name('laporan-bank');
    Route::get('/laporan-bank/rekap', [LaporanBankController::class, 'rekap'])->name('laporan-bank.rekap');



});


// ⚙️ Semua User Login (Profile)
Route::middleware(['auth', 'prevent-back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// 🔁 Import Route Breeze (login, logout, reset password)
require __DIR__ . '/auth.php';
