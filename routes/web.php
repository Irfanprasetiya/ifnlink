<?php

use App\Http\Controllers\AdminStokController;
use App\Http\Controllers\Developer\PembayaranController;
use Illuminate\Support\Facades\Route;

// ==================== Controllers: Public & Auth ====================
use App\Http\Controllers\LandingController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;

// ==================== Controllers: Developer ====================
use App\Http\Controllers\Developer\ActivityLogController;
use App\Http\Controllers\Developer\BackupController;
use App\Http\Controllers\Developer\DevDashboardController;
use App\Http\Controllers\Developer\PelangganController;
use App\Http\Controllers\Developer\PlanController as DeveloperPlanController;

// ==================== Controllers: Admin & Super Admin ====================
use App\Http\Controllers\AkunPengeluaranController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BankSaldoController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JenisTransaksiController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LabaRugiController;
use App\Http\Controllers\LanggananController;
use App\Http\Controllers\LaporanBankAdminController;
use App\Http\Controllers\LaporanSaldoController;
use App\Http\Controllers\PengeluaranKasController;
use App\Http\Controllers\ProdukKonterController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\SaldoAwalController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\VoucherController;

// ==================== Controllers: User ====================
use App\Http\Controllers\detailKonterController;
use App\Http\Controllers\Developer\MaintenanceController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\laporanKonterController;
use App\Http\Controllers\LaporanBankController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\TransaksiBankController;

// ==================== Controllers: Profile ====================
use App\Http\Controllers\ProfileController;

// ==================== Controllers: Unused (keep for auth.php) ====================
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\ReturController;
use App\Http\Controllers\SaldoGudangController;
use App\Http\Controllers\StokHistoryController;
use App\Http\Controllers\StokOpnameController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES (Tanpa Login)
|--------------------------------------------------------------------------
*/
Route::get('/', [LandingController::class, 'index'])->name('landing.index');
Route::get('/pricing', [LandingController::class, 'pricing'])->name('landing.pricing');
Route::get('/kebijakan-privasi', function () {
    return view('legal.kebijakan-privasi');
})->name('kebijakan-privasi');

Route::get('/syarat-ketentuan', function () {
    return view('legal.syarat-ketentuan');
})->name('syarat-ketentuan');

Route::get('/register-agen', [TenantController::class, 'showRegister'])
    ->name('agen.register')
    ->middleware('guest'); // ✅ Hanya untuk yang belum login
Route::post('/register-agen', [TenantController::class, 'storeRegister'])->name('agen.store');

Route::get('/checkout/{plan}', [PaymentController::class, 'checkout'])->name('checkout');
Route::post('/pay', [PaymentController::class, 'pay'])->name('pay');
Route::get('/pay', fn() => redirect()->route('agen.register'));
Route::get('/payment/finish', [PaymentController::class, 'finish'])->name('payment.finish');
Route::post('/midtrans/notification', [PaymentController::class, 'notification'])
    ->name('midtrans.notification')
    ->withoutMiddleware(['verify.csrf.token']);

Route::middleware(['guest.redirect', 'prevent-back'])->group(function () {
    Route::get('/login', fn() => view('auth.login'))->name('login');
});

Route::get('/dashboard/pending', function () {
    $tenant = auth()->user()->tenant;

    // ✅ Cek pending ATAU suspended
    if (!$tenant || !in_array($tenant->status_langganan, ['pending', 'suspended'])) {
        return redirect()->route('dashboard');
    }

    return view('dashboard.pending', compact('tenant'));
})->name('dashboard.pending');
/*
|--------------------------------------------------------------------------
| DEVELOPER ROUTES (Tanpa check.tenant.active)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:developer'])->prefix('developer')->name('developer.')->group(function () {
    Route::get('/dashboard', [DevDashboardController::class, 'index'])->name('dashboard');

    Route::get('/log-aktivitas', [ActivityLogController::class, 'index'])->name('log.index');
    Route::delete('/log-aktivitas/clear', [ActivityLogController::class, 'clear'])->name('log.clear');

    Route::prefix('paket')->name('paket.')->group(function () {
        Route::get('/', [DeveloperPlanController::class, 'index'])->name('index');
        Route::post('/', [DeveloperPlanController::class, 'store'])->name('store');
        Route::put('/{plan}', [DeveloperPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [DeveloperPlanController::class, 'destroy'])->name('destroy');
        Route::patch('/{plan}/toggle', [DeveloperPlanController::class, 'toggleActive'])->name('toggle');
    });

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', [PelangganController::class, 'index'])->name('index');
        Route::get('/{id}', [PelangganController::class, 'show'])->name('show');
        Route::put('/{id}/status', [PelangganController::class, 'updateStatus'])->name('status');
        Route::post('/{id}/login-as', [PelangganController::class, 'loginAs'])->name('login-as');
        Route::post('/logout-impersonate', [PelangganController::class, 'logoutImpersonate'])->name('logout-impersonate');
        Route::post('/{id}/restore', [PelangganController::class, 'restore'])->name('restore');
        Route::delete('/{id}', [PelangganController::class, 'destroy'])->name('destroy');
        Route::delete('/{id}/force', [PelangganController::class, 'forceDelete'])->name('force-delete');
    });

    // Riwayat Pembayaran
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    // ✅ Export PDF - Harus sebelum route {id}
    Route::get('/pembayaran/export/pdf', [PembayaranController::class, 'exportPdf'])->name('pembayaran.export.pdf');
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show'])->name('pembayaran.show');

    Route::get('/backup', [BackupController::class, 'index'])->name('backup.index');
    Route::get('/backup/download', [BackupController::class, 'download'])->name('backup.download');
    Route::post('/backup/restore', [BackupController::class, 'restore'])->name('backup.restore');
    Route::delete('/backup/{filename}', [BackupController::class, 'delete'])->name('backup.delete');

    // Maintenance Mode
    Route::get('/maintenance', [MaintenanceController::class, 'index'])->name('maintenance.index');
    Route::post('/maintenance/enable', [MaintenanceController::class, 'enable'])->name('maintenance.enable');
    Route::post('/maintenance/disable', [MaintenanceController::class, 'disable'])->name('maintenance.disable');
    Route::post('/maintenance/toggle', [MaintenanceController::class, 'toggle'])->name('maintenance.toggle');
});

/*
|--------------------------------------------------------------------------
| SUPER ADMIN ONLY (DENGAN check.tenant.active)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.pending', 'check.tenant.active', 'role:super_admin', 'prevent-back'])->group(function () {
    Route::get('/laporan-saldo', [LaporanSaldoController::class, 'index'])->name('laporan_saldo.index');
});

/*
|--------------------------------------------------------------------------
| ADMIN & SUPER ADMIN ROUTES (DENGAN check.tenant.active)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'check.pending', 'check.tenant.active', 'role:super_admin,admin', 'prevent-back'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Status Langganan & Upgrade
    Route::get('/status-langganan', [LanggananController::class, 'index'])->name('status.langganan');
    Route::get('/status-langganan/cek-status', [LanggananController::class, 'cekStatus'])
        ->name('status.langganan.cek')
        ->middleware('auth');
    Route::get('/status-langganan/perpanjang', [LanggananController::class, 'perpanjang'])->name('status.perpanjang');
    Route::post('/status-langganan/upload-bukti', [LanggananController::class, 'uploadBukti'])->name('status.upload-bukti');
    Route::get('/status-langganan/invoice/{id}', [LanggananController::class, 'downloadInvoice'])->name('status.invoice');
    Route::post('/status-langganan/batalkan', [LanggananController::class, 'batalkan'])->name('status.batalkan');
    Route::get('/upgrade', [LanggananController::class, 'upgrade'])->name('upgrade');
    Route::post('/upgrade/proses', [LanggananController::class, 'prosesUpgrade'])->name('status.upgrade-proses');

    // Saldo Awal
    Route::get('/saldo-awal', [SaldoAwalController::class, 'index'])->name('saldo.index');
    Route::post('/simpan-saldo-baru', [SaldoAwalController::class, 'store'])->name('saldo.store');
    Route::get('/saldo-awal/cek/{cabang_id}/{user_id}', [SaldoAwalController::class, 'cekSaldoAwal'])->name('saldo.cek');

    // Transaksi Bank
    Route::get('/trx-bank', [BankSaldoController::class, 'index'])->name('trx-bank.index');
    Route::post('/trx-bank', [BankSaldoController::class, 'store'])->name('trx-bank.store');
    Route::post('/trx-bank/transfer', [BankSaldoController::class, 'transferAntarCabang'])->name('trx-bank.transfer');
    Route::delete('/trx-bank/{bankSaldo}', [BankSaldoController::class, 'destroy'])->name('trx-bank.destroy');
    Route::get('/get-users-by-cabang/{cabang_id}', [BankSaldoController::class, 'getUsersByCabang'])->name('get.users.by.cabang');
    Route::post('/cek-saldo-awal-bank', [BankSaldoController::class, 'cekSaldoAwal'])->name('cek.saldo.awal.bank');

    // Stok Produk
    Route::get('/admin/stok', [AdminStokController::class, 'index'])->name('admin.stok.index');
    Route::get('/admin/stok/create', [AdminStokController::class, 'create'])->name('admin.stok.create');
    Route::post('/admin/stok', [AdminStokController::class, 'store'])->name('admin.stok.store');
    Route::get('/admin/stok/riwayat', [AdminStokController::class, 'riwayat'])->name('admin.stok.riwayat');

    // Manajemen Users
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users/register', [UserRegisterController::class, 'store'])->name('users.register');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::post('/users/{userId}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    // Manajemen Cabang
    Route::get('/cabang', [CabangController::class, 'index'])->name('data_master.cabang.index');
    Route::post('/cabang', [CabangController::class, 'store'])->name('data_master.cabang.store');
    Route::put('/cabang/{id}', [CabangController::class, 'update'])->name('cabang.update');
    Route::delete('/cabang/{id}', [CabangController::class, 'destroy'])->name('cabang.destroy');

    // Data Master
    Route::prefix('data_master')->name('data_master.')->group(function () {
        Route::resource('vouchers', VoucherController::class);
        Route::resource('kategoris', KategoriController::class)->only(['index', 'store', 'update', 'edit', 'destroy']);
        Route::resource('produk_konter', ProdukKonterController::class);
        // Route::resource('jenis-transaksi', JenisTransaksiController::class);

        Route::get('daftar_bank', [BankController::class, 'index'])->name('daftar_bank.index');
        Route::post('daftar_bank', [BankController::class, 'store'])->name('daftar_bank.store');
        Route::put('daftar_bank/{id}', [BankController::class, 'update'])->name('daftar_bank.update');
        Route::delete('daftar_bank/{id}', [BankController::class, 'destroy'])->name('daftar_bank.destroy');

        Route::controller(AkunPengeluaranController::class)->group(function () {
            Route::get('/akun-pengeluaran', 'index')->name('akun_pengeluaran.index');
            Route::post('/akun-pengeluaran', 'store')->name('akun_pengeluaran.store');
            Route::put('/akun-pengeluaran/{akun}', 'update')->name('akun_pengeluaran.update');
            Route::delete('/akun-pengeluaran/{akun}', 'destroy')->name('akun_pengeluaran.destroy');
            Route::get('akun-pengeluaran/check/{nama}', function ($nama) {
                return response()->json(['exists' => \App\Models\AkunPengeluaran::where('nama_akun', $nama)->exists()]);
            });
        });
    });

    // Pengeluaran Kas
    Route::get('/pengeluaran', [PengeluaranKasController::class, 'index'])->name('pengeluaran.index');
    Route::post('/pengeluaran', [PengeluaranKasController::class, 'store'])->name('pengeluaran.store');
    Route::put('/pengeluaran/{id}', [PengeluaranKasController::class, 'update'])->name('pengeluaran.update');
    Route::delete('/pengeluaran/{id}', [PengeluaranKasController::class, 'destroy'])->name('pengeluaran.destroy');

    // Barang Masuk
    Route::resource('barang_masuk', BarangMasukController::class)->except(['show']);

    Route::get('/admin/pos/laporan', [PosController::class, 'adminLaporan'])->name('admin.pos.laporan');
    Route::get('/admin/pos/laporan/pdf', [PosController::class, 'adminLaporanPdf'])->name('admin.pos.laporan.pdf');
    Route::get('/admin/pos/laporan/excel', [PosController::class, 'adminLaporanExcel'])->name('admin.pos.laporan.excel');
    Route::delete('/admin/pos/{id}', [PosController::class, 'destroy'])->name('admin.pos.destroy');
    Route::get('/admin/pos/{id}/edit', [PosController::class, 'edit'])->name('admin.pos.edit');
    Route::put('/admin/pos/{id}/update', [PosController::class, 'update'])->name('admin.pos.update');

    // Stok Opname
    Route::get('/stok-opname', [StokOpnameController::class, 'index'])->name('data_master.stok_opname.index');
    Route::get('/stok-opname/create', [StokOpnameController::class, 'create'])->name('data_master.stok_opname.create');
    Route::post('/stok-opname', [StokOpnameController::class, 'store'])->name('data_master.stok_opname.store');
    Route::get('/stok-opname/produk/{cabangId}', [StokOpnameController::class, 'getProdukByCabang'])->name('data_master.stok_opname.produk');
    Route::get('/stok-opname/{kode}', [StokOpnameController::class, 'show'])->name('data_master.stok_opname.show');
    Route::get('/stok-opname/{kode}/pdf', [StokOpnameController::class, 'pdf'])->name('data_master.stok_opname.pdf');

    // stok history
    Route::get('/stok-history', [StokHistoryController::class, 'index'])->name('stok_history.index');
    Route::get('/stok-laporan', [StokHistoryController::class, 'laporan'])->name('stok_history.laporan');
    Route::get('/stok-menipis', [StokHistoryController::class, 'stokMenipis'])->name('stok_history.menipis');
    // Halaman stok menipis
    Route::get('/stok-menipis', [StokHistoryController::class, 'halamanMenipis'])->name('stok_history.menipis');

    // API JSON
    Route::get('/api/stok-menipis', [StokHistoryController::class, 'stokMenipis'])->name('stok_history.api-menipis');


    // Laporan Bank Admin
    Route::get('/laporan-bank-admin', [LaporanBankAdminController::class, 'index'])->name('laporan-bank.admin.index');
    Route::get('/laporan_bank/rekap', [LaporanBankAdminController::class, 'rekap'])->name('laporan_bank.rekap');
    Route::get('/laporan-bank-admin/get-users-by-cabang/{cabangId}', [LaporanBankAdminController::class, 'getUsersByCabang']);
    Route::put('/laporan-bank/{id}', [LaporanBankAdminController::class, 'update'])->name('laporan-bank.update');
    Route::delete('/laporan-bank/{id}', [LaporanBankAdminController::class, 'destroy'])->name('laporan-bank.destroy');
    Route::get('/laporan-bank/rekap/pdf', [LaporanBankAdminController::class, 'exportRekapPdf'])->name('laporan-bank.rekap.pdf');
    Route::get('/laporan-bank/rekap/excel', [LaporanBankAdminController::class, 'exportRekapExcel'])->name('laporan-bank.rekap.excel');

    // Laba Rugi
    Route::get('/laba-rugi', [LabaRugiController::class, 'index'])->name('laba_rugi.index');
    Route::get('/laba-rugi/pdf', [LabaRugiController::class, 'exportPdf'])->name('laba_rugi.pdf');
    Route::get('/laba-rugi/excel', [LabaRugiController::class, 'exportExcel'])->name('laba_rugi.excel');

    // Rekap
    Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    Route::get('/rekap/pdf', [RekapController::class, 'exportPdf'])->name('rekap.pdf');
    Route::get('/rekap/excel', [RekapController::class, 'exportExcel'])->name('rekap.excel');

    // Retur Admin
    Route::get('/admin/retur', [ReturController::class, 'index'])->name('admin.retur.index');
    Route::get('/admin/retur/{id}', [ReturController::class, 'show'])->name('admin.retur.show');
    Route::post('/admin/retur/{id}/approve', [ReturController::class, 'approve'])->name('admin.retur.approve');
    Route::post('/admin/retur/{id}/reject', [ReturController::class, 'reject'])->name('admin.retur.reject');
});

/*
|--------------------------------------------------------------------------
| USER ROUTES (DENGAN check.tenant.active)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:user', 'check.tenant.active', 'prevent-back'])->group(function () {
    Route::get('/main', [LandingPageController::class, 'index'])->name('main');

    Route::get('/transaksi-konter', [MainPageController::class, 'index'])->name('vouchers');
    Route::get('/transaksi-konter/{id}', [detailKonterController::class, 'show'])->name('transaksi.konter.detail');
    Route::get('/detail', [detailKonterController::class, 'index'])->name('detail');
    Route::get('/detail/{id}', [detailKonterController::class, 'show'])->name('detail.show');
    Route::get('/laporanKonter', [laporanKonterController::class, 'index'])->name('laporan_konter');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');

    Route::get('/transaksi-bank', [TransaksiBankController::class, 'index'])->name('transaksi-bank');
    Route::post('/transaksi-bank', [TransaksiBankController::class, 'store'])->name('transaksi_banks.store');
    Route::get('/transaksi-bank/detail/{bank_id}', [TransaksiBankController::class, 'detail'])->name('transaksi_banks.detail');

    // ✅ POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos', [PosController::class, 'store'])->name('pos.store');
    Route::get('/pos/laporan', [PosController::class, 'laporan'])->name('pos.laporan');
    Route::get('/pos/laporan/pdf', [PosController::class, 'laporanPdf'])->name('pos.laporan.pdf');
    Route::get('/pos/struk/{kode}', [PosController::class, 'struk'])->name('pos.struk');

    // Retur Routes
    Route::get('/retur/create', [ReturController::class, 'create'])->name('retur.create');
    Route::post('/retur', [ReturController::class, 'store'])->name('retur.store');
    Route::get('/retur/riwayat', [ReturController::class, 'riwayat'])->name('retur.riwayat');

    Route::get('/laporanBank', [LaporanBankController::class, 'index'])->name('laporan-bank');
    Route::get('/laporan-bank/rekap', [LaporanBankController::class, 'rekap'])->name('laporan-bank.rekap');
    Route::get('/laporan-setoran/pdf', [LaporanBankController::class, 'exportPdf'])->name('laporan-setoran.pdf');
    Route::get('/laporan-setoran/excel', [LaporanBankController::class, 'exportExcel'])->name('laporan-setoran.excel');
});

// halaman error 404 untuk semua route yang tidak ditemukan
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});

/*
|--------------------------------------------------------------------------
| PROFILE ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'prevent-back'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



/*
|--------------------------------------------------------------------------
| AUTH ROUTES (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';

Route::post('/deploy', function () {
    $secret = 'rahasia123';
    $signature = request()->header('X-Hub-Signature-256');
    $expected = 'sha256=' . hash_hmac('sha256', request()->getContent(), $secret);

    if (!hash_equals($expected, $signature)) {
        return response()->json(['error' => 'Invalid signature'], 403);
    }

    exec('nohup /var/www/deploy.sh > /var/www/deploy.log 2>&1 &');

    return response()->json(['status' => 'Deploy started']);
});
// CI/CD Test Mon, Aug 24, 2026 11:23:18 AM
// test
// Test CI/CD Mon, Aug 24, 2026 12:26:26 PM
// Test CI/CD Mon, Aug 24, 2026 12:31:35 PM
// Test Mon, Aug 24, 2026 12:36:07 PM
