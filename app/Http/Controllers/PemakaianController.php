<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\Pemakaian;

class PemakaianController extends Controller
{
    public function index(Request $request)
    {
        // Mulai query dasar
        $query = Pemakaian::query()->with('pelanggan');

        // Filter berdasarkan pencarian (NoKontrol atau nama pelanggan)
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('NoKontrol', 'like', "%{$search}%")
                  ->orWhereHas('pelanggan', function($q2) use ($search) {
                      $q2->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // Filter berdasarkan tahun
        if ($request->has('tahun') && !empty($request->tahun)) {
            $query->where('tahun', $request->tahun);
        }

        // Filter berdasarkan bulan
        if ($request->has('bulan') && !empty($request->bulan)) {
            $query->where('bulan', $request->bulan);
        }

        // Filter berdasarkan status
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Ambil data dengan pagination
        $pemakaians = $query->latest()->paginate(10);

        return view('pemakaian.index', compact('pemakaians'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::with('jenisPlg')->get();
        return view('pemakaian.create', compact('pelanggans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'NoKontrol' => 'required|exists:pelanggans,NoKontrol',
            'tahun' => 'required|integer',
            'bulan' => 'required|string',
            'meterawal' => 'required|integer',
            'meterakhir' => 'required|integer|gte:meterawal',
            'jumlahpakai' => 'required|integer',
            'biayabebanpemakai' => 'required|numeric',
            'biayapemakaian' => 'required|numeric',
            'status' => 'required|string',
            'jumlahbayar' => 'required|numeric',
        ]);

        // Periksa apakah data untuk bulan ini sudah ada dan sudah dibayar
        $existingRecord = Pemakaian::where('NoKontrol', $request->NoKontrol)
                                  ->where('tahun', $request->tahun)
                                  ->where('bulan', $request->bulan)
                                  ->where(function($query) {
                                      $query->where('status', 'Lunas')
                                            ->orWhere('status', 'Sudah Bayar');
                                  })
                                  ->first();

        if ($existingRecord) {
            return redirect()->back()->with('error', 'Data pemakaian untuk bulan ini sudah dibayar.');
        }

        // Calculate values here instead of using mutators
        $jumlahPakai = max(0, $request->meterakhir - $request->meterawal);
        $tarifPerKwh = 1500; // Tarif per kWh
        $biayaPemakaian = $jumlahPakai * $tarifPerKwh;
        $jumlahBayar = $biayaPemakaian + $request->biayabebanpemakai;

        Pemakaian::create([
            'NoKontrol' => $request->NoKontrol,
            'tahun' => $request->tahun,
            'bulan' => $request->bulan,
            'meterawal' => $request->meterawal,
            'meterakhir' => $request->meterakhir,
            'jumlahpakai' => $jumlahPakai,
            'biayabebanpemakai' => $request->biayabebanpemakai,
            'biayapemakaian' => $biayaPemakaian,
            'status' => $request->status,
            'jumlahbayar' => $jumlahBayar,
        ]);

        return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil ditambahkan.');
    }

public function edit($id)
{
    $pemakaian = Pemakaian::with('pelanggan.jenisPlg')->findOrFail($id);
    $pelanggans = Pelanggan::with('jenisPlg')->get();
    return view("pemakaian.edit", compact('pemakaian', 'pelanggans'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'NoKontrol' => 'required|exists:pelanggans,NoKontrol',
        'tahun' => 'required|integer',
        'bulan' => 'required|string',
        'meterawal' => 'required|integer',
        'meterakhir' => 'required|integer|gte:meterawal',
        'jumlahpakai' => 'required|integer',
        'biayabebanpemakai' => 'required|numeric',
        'biayapemakaian' => 'required|numeric',
        'status' => 'required|string|in:Belum Bayar,Lunas',
        'jumlahbayar' => 'required|numeric',
    ]);

    $pemakaian = Pemakaian::findOrFail($id);

    // Periksa apakah data untuk bulan ini sudah ada dan sudah dibayar
    $existingRecord = Pemakaian::where('NoKontrol', $request->NoKontrol)
                                ->where('tahun', $request->tahun)
                                ->where('bulan', $request->bulan)
                                ->where('id', '!=', $id)
                                ->where(function($query) {
                                    $query->where('status', 'Lunas')
                                          ->orWhere('status', 'Sudah Bayar');
                                })
                                ->first();

    if ($existingRecord) {
        return redirect()->back()->with('error', 'Data pemakaian untuk bulan ini sudah dibayar.');
    }

    // Calculate values
    $jumlahPakai = max(0, $request->meterakhir - $request->meterawal);
    $tarifPerKwh = 1500; // Tarif per kWh
    $biayaPemakaian = $jumlahPakai * $tarifPerKwh;
    $jumlahBayar = $biayaPemakaian + $request->biayabebanpemakai;

    // Update the record
    $pemakaian->update([
        'NoKontrol' => $request->NoKontrol,
        'tahun' => $request->tahun,
        'bulan' => $request->bulan,
        'meterawal' => $request->meterawal,
        'meterakhir' => $request->meterakhir,
        'jumlahpakai' => $jumlahPakai,
        'biayabebanpemakai' => $request->biayabebanpemakai,
        'biayapemakaian' => $biayaPemakaian,
        'status' => $request->status,
        'jumlahbayar' => $jumlahBayar,
    ]);

    return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil diperbarui.');
}

    public function destroy($id)
    {
        $pemakaian = Pemakaian::findOrFail($id);
        $pemakaian->delete();

        return redirect()->route('pemakaian.index')->with('success', 'Data pemakaian berhasil dihapus.');
    }

    public function history()
    {
        $pemakaians = Pemakaian::with('pelanggan')->paginate(5);
        return view('history.index', compact('pemakaians'));
    }
}
