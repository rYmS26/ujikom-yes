<?php

use App\Models\Pelanggan;
use App\Models\Pemakaian;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AkunController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PemakaianController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\JenisPelangganController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Dashboard routes
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/api/chart-data', [DashboardController::class, 'getChartData'])
    ->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/pelanggan', [PelangganController::class,'pelanggan'])->name('customers.index');
    Route::delete('/pelanggan/{id}', [PelangganController::class, 'deletePelanggan'])->name('customers.destroy');
    Route::put('/pelanggan/edit/{id}', [PelangganController::class, 'updatePelanggan'])->name('customers.update');
    Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'showFormEdit'])->name('customers.update');

    Route::get('/pelanggan/add', [PelangganController::class,'showFormAdd'])->name('customers.create');
    Route::post('/pelanggan/add', [PelangganController::class,'storePelanggan'])->name('customers.store');

    Route::get('/jenisPelanggan', [JenisPelangganController::class, 'showJenisPelanggan'])->name('customers.jenis_pelanggan');
    Route::delete('/jenisPelanggan/{id}', [JenisPelangganController::class, 'destroyJenisPelanggan'])->name('customers.delete_jenis_pelanggan');
    Route::get('/jenisPelanggan/add', [JenisPelangganController::class, 'formJenisPelanggan'])->name('customers.createJenisPlg');
    Route::post('/jenisPelanggan/add', [JenisPelangganController::class, 'storeJenisPelanggan'])->name('customers.store_jenis_pelanggan');
    Route::get('/jenisPelanggan/edit/{id}', [JenisPelangganController::class, 'editJenisPelanggan'])->name('customers.editJenisPlg');
    Route::put('/jenisPelanggan/edit/{id}', [JenisPelangganController::class, 'updateJenisPelanggan'])->name('customers.edit_jenis_pelanggan');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/petugas', [AkunController::class, 'index'])->name('admin.index');
    Route::get('/petugas/tambah', [AkunController::class, 'create'])->name('admin.create');
    Route::post('/petugas/tambah', [AkunController::class, 'store'])->name('admin.store');
    Route::get('/petugas/edit/{id}', [AkunController::class, 'edit'])->name('admin.edit');
    Route::put('/petugas/edit/{id}', [AkunController::class, 'update'])->name('admin.update');
    Route::delete('/petugas/{id}', [AkunController::class, 'destroyAkun'])->name('admin.akunDestroy');

    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/tambah', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran/tambah', [PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/edit/{id}', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
    Route::put('/pembayaran/edit/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');
    Route::delete('/pembayaran/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');
    Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
    Route::get('/pembayaran/{noKontrol}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/pembayaran/process/{id}', [PembayaranController::class, 'processPayment'])->name('pembayaran.process');
    Route::get('/pembayaran/search', [PembayaranController::class, 'search'])->name('pembayaran.search');
    Route::post('/pembayaran/process-multiple/{noKontrol}', [PembayaranController::class, 'processMultiplePayments'])->name('pembayaran.process-multiple');
Route::post('/pembayaran/multiple-receipt/{noKontrol}', [PembayaranController::class, 'generateMultipleReceiptPdf'])->name('pembayaran.multiple-receipt');

    Route::get('/pembayaran/{noKontrol}/pdf', [PembayaranController::class, 'generatePdf'])->name('pembayaran.pdf');

    // Laporan (Reports) Routes
Route::prefix('laporan')->name('laporan.')->group(function () {
    // Main report dashboard
    Route::get('/', [LaporanController::class, 'index'])->name('index');

    // Monthly reports
    Route::get('/monthly', [LaporanController::class, 'monthly'])->name('monthly');
    Route::get('/monthly/pdf', [LaporanController::class, 'monthlyPdf'])->name('monthly.pdf');

    // Yearly reports
    Route::get('/yearly', [LaporanController::class, 'yearly'])->name('yearly');
    Route::get('/yearly/pdf', [LaporanController::class, 'yearlyPdf'])->name('yearly.pdf');

    // Date range reports
    Route::get('/date-range', [LaporanController::class, 'dateRange'])->name('date-range');

    // Status reports (paid/unpaid)
    Route::get('/status', [LaporanController::class, 'status'])->name('status');
    Route::get('/status/pdf', [LaporanController::class, 'statusPdf'])->name('status.pdf');
});

    Route::get('/tarif', [TarifController::class, 'index'])->name('tarif.index');
    Route::get('/tarif/tambah', [TarifController::class, 'create'])->name('tarif.create');
    Route::post('/tarif/tambah', [TarifController::class, 'store'])->name('tarif.store');
    Route::get('/tarif/edit/{id}', [TarifController::class, 'edit'])->name('tarif.edit');
    Route::put('/tarif/edit/{id}', [TarifController::class, 'update'])->name('tarif.update');
    Route::delete('/tarif/{id}', [TarifController::class, 'destroy'])->name('tarif.destroy');

    Route::get('/pemakaian', [PemakaianController::class, 'index'])->name('pemakaian.index');
    Route::get('/pembayaran/{nokontrol}', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::get('/pemakaian/tambah', [PemakaianController::class, 'create'])->name('pemakaian.create');
    Route::post('/pemakaian/tambah', [PemakaianController::class, 'store'])->name('pemakaian.store');
    Route::get('/pemakaian/edit/{id}', [PemakaianController::class, 'edit'])->name('pemakaian.edit');
    Route::put('/pemakaian/edit/{id}', [PemakaianController::class, 'update'])->name('pemakaian.update');
    Route::delete('/pemakaian/{id}', [PemakaianController::class, 'destroy'])->name('pemakaian.destroy');
    // Route for getting last meter reading
    Route::get('/get-last-meter-reading/{noKontrol}', [PemakaianController::class, 'getLastMeterReading']);

    Route::get('/history', [PemakaianController::class, 'history'])->name('history.index');
});
Route::get('/', [HomeController::class,'index'])->name('home');
 // Route untuk memeriksa bulan yang sudah dibayar
Route::get('/check-paid-months/{noKontrol}', function ($noKontrol) {
    // Ambil semua data pemakaian untuk pelanggan ini yang sudah lunas
    $pemakaians = Pemakaian::where('NoKontrol', $noKontrol)
                          ->where(function($query) {
                              $query->where('status', 'Lunas')
                                    ->orWhere('status', 'Sudah Bayar');
                          })
                          ->get();

    // Inisialisasi array untuk menyimpan bulan yang sudah dibayar
    $paidMonths = [];

    // Loop melalui semua data pemakaian
    foreach ($pemakaians as $pemakaian) {
        $tahun = $pemakaian->tahun;
        $bulan = $pemakaian->bulan;

        // Jika tahun belum ada dalam array, tambahkan
        if (!isset($paidMonths[$tahun])) {
            $paidMonths[$tahun] = [];
        }

        // Tambahkan bulan ke array
        $paidMonths[$tahun][] = $bulan;
    }

    return response()->json($paidMonths);
});
Route::get('/pembayaran/receipt/{id}/pdf', [PembayaranController::class, 'generateReceiptPdf'])->name('pembayaran.receipt.pdf');
Route::get('/search', [SearchController::class, 'search'])->name('search.index');

require __DIR__.'/auth.php';
require __DIR__.'/api.php';
