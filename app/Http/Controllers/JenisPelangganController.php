<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\JenisPlg;
use Illuminate\Support\Facades\DB;
use Log;

class JenisPelangganController extends Controller
{
    // Show page for displaying all jenis pelanggan
    public function showJenisPelanggan()
    {
        $jenisPelanggan = JenisPlg::paginate(5);
        return view('customers.jenis_pelanggan', compact('jenisPelanggan'));
    }

    public function formJenisPelanggan()
    {
        return view('customers.createJenisPlg');
    }

    // Store a new jenis pelanggan
    public function storeJenisPelanggan(Request $request)
    {
        // Add debugging to see what's coming in
        Log::info('Form data received for Jenis Pelanggan:', $request->all());

        $request->validate([
            'id_jenis' => 'required|string|max:50|unique:jenis_plg,id_jenis',
            'nama_jenis' => 'required|string|max:100',
        ]);

        try {
            // Create a new JenisPlg record with default values for biayabeban and tarifkwh
            $jenisPelanggan = JenisPlg::create([
                'id_jenis' => $request->id_jenis,
                'nama_jenis' => $request->nama_jenis,
                'biayabeban' => 0, // Default value, will be updated by TarifController
                'tarifkwh' => 0,   // Default value, will be updated by TarifController
            ]);

            // Log the successful creation
            Log::info('Jenis Pelanggan created:', $jenisPelanggan->toArray());

            return redirect()->route('customers.jenis_pelanggan')
                ->with('success', 'Jenis Pelanggan berhasil ditambahkan. Silakan tambahkan tarif untuk jenis pelanggan ini.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating Jenis Pelanggan: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menambahkan Jenis Pelanggan: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Edit jenis pelanggan
    public function editJenisPelanggan($id)
{
    $jenisPelanggan = JenisPlg::where('id_jenis', $id)->firstOrFail();
    return view('customers.editJenisPlg', compact('jenisPelanggan'));
}

    // Update a specific jenis pelanggan
    public function updateJenisPelanggan(Request $request, $id)
    {
        $request->validate([
            'id_jenis' => "required|string|max:50|unique:jenis_plg,id_jenis,{$id},id_jenis",
            'nama_jenis' => 'required|string|max:100',
        ]);

        $jenisPelanggan = JenisPlg::where('id_jenis', $id)->firstOrFail();

        // Only update id_jenis and nama_jenis, leave biayabeban and tarifkwh unchanged
        $jenisPelanggan->update([
            'id_jenis' => $request->id_jenis,
            'nama_jenis' => $request->nama_jenis,
        ]);

        return redirect()->route('customers.jenis_pelanggan')
            ->with('success', 'Jenis Pelanggan berhasil diperbarui.');
    }

    public function destroyJenisPelanggan($id)
    {
        $jenisPelanggan = JenisPlg::where('id_jenis', $id)->firstOrFail();
        $jenisPelanggan->delete();

        return redirect()->route('customers.jenis_pelanggan')
            ->with('success', 'Jenis Pelanggan berhasil dihapus.');
    }
}
