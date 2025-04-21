<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Pemakaian;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route untuk mendapatkan bulan yang sudah dibayar
Route::get('/pemakaian/paid-months/{noKontrol}', function ($noKontrol) {
    // Ambil semua data pemakaian untuk pelanggan ini
    $pemakaians = Pemakaian::where('NoKontrol', $noKontrol)
                          ->where('status', 'Lunas')
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
