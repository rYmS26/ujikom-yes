<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('query');
        $pemakaian = collect();
        $pelanggan = null;

        if ($query) {
            // Fetch data based on NoKontrol
            $pemakaian = Pemakaian::where('NoKontrol', $query)
                ->with('pelanggan')
                ->orderBy('tahun', 'desc')
                ->orderBy('bulan', 'desc')
                ->get();

            // Get customer data if available
            $pelanggan = Pelanggan::where('NoKontrol', $query)->first();
        }

        return view('welcome', compact('pemakaian', 'query', 'pelanggan'));
    }
}
