<?php

namespace App\Http\Controllers;

use App\Models\JenisPlg;
use App\Models\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function pelanggan() {
        $Pelanggan = Pelanggan::with('jenisPlg:id_jenis')->paginate(5);
        return view("customers.index", compact("Pelanggan"));
    }

    public function showFormAdd() {
        $jenisPelanggan = JenisPlg::all();
        return view("customers.create", compact("jenisPelanggan"));
    }

    public function storePelanggan(Request $request) {
        // dd($request->all());
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'jenis_plg' => 'required|string|max:50|exists:jenis_plg,id_jenis',
        ]);

        $validatedData['NoKontrol'] = 'NK-' . strtoupper(uniqid());

        Pelanggan::create($validatedData);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil ditambahkan.');
    }

    public function deletePelanggan($id) {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil dihapus.');
    }

    public function showFormEdit($id) {
        $pelanggan = Pelanggan::findOrFail($id);
        $jenisPelanggan = JenisPlg::all();
        return view("customers.edit", compact("pelanggan", "jenisPelanggan"));
    }

    public function updatePelanggan(Request $request, $id) {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'telepon' => 'required|string|max:15',
            'jenis_plg' => 'required|string|max:50|exists:jenis_plg,id_jenis',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->update($validatedData);

        return redirect()->route('customers.index')->with('success', 'Pelanggan berhasil diperbarui.');
    }
}
