<?php

namespace App\Http\Controllers;

use App\Models\JenisPlg;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use App\Models\TarifLog;
use Illuminate\Support\Facades\Log;

class TarifController extends Controller
{
    // Show all records
    public function index()
    {
        $tarifLogs = TarifLog::with('jenisPlg')->paginate(5);
        return view('tarif.index', compact('tarifLogs'));
    }

    // Show create form
    public function create()
    {
        // Get all jenis pelanggan for dropdown
        $jenisPelanggan = JenisPlg::all();
        $pelanggans = Pelanggan::all();
        return view('tarif.create', compact('jenisPelanggan', 'pelanggans'));
    }

    // Store new record
    public function store(Request $request)
    {
        $request->validate([
            'id_jenis' => 'required|string|max:50|exists:jenis_plg,id_jenis',
            'tarifkwh' => 'required|numeric|min:0',
            'biayabeban' => 'required|numeric|min:0',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_mulai',
        ]);

        try {
            // Create the TarifLog record
            $tarifLog = TarifLog::create($request->all());

            // Now update the related JenisPlg record
            $jenisPlg = JenisPlg::where('id_jenis', $request->id_jenis)->first();
            if ($jenisPlg) {
                // Update the JenisPlg data with the new tarifkwh and biayabeban
                $jenisPlg->tarifkwh = $request->tarifkwh;
                $jenisPlg->biayabeban = $request->biayabeban;
                $jenisPlg->save();

                Log::info('JenisPlg updated with new tarif values', [
                    'id_jenis' => $jenisPlg->id_jenis,
                    'tarifkwh' => $jenisPlg->tarifkwh,
                    'biayabeban' => $jenisPlg->biayabeban
                ]);
            } else {
                Log::warning('JenisPlg not found for updating tarif', [
                    'id_jenis' => $request->id_jenis
                ]);
            }

            return redirect()->route('tarif.index')
                ->with('success', 'Tarif berhasil ditambahkan dan Jenis Pelanggan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error creating tarif: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menambahkan Tarif: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show edit form
    public function edit($id)
    {
        $tarifLog = TarifLog::findOrFail($id);
        $jenisPelanggan = JenisPlg::all();
        return view('tarif.edit', compact('tarifLog', 'jenisPelanggan'));
    }

    // Update record
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_jenis' => 'required|string|max:50|exists:jenis_plg,id_jenis',
            'tarifkwh' => 'required|numeric|min:0',
            'biayabeban' => 'required|numeric|min:0',
            'berlaku_mulai' => 'required|date',
            'berlaku_sampai' => 'nullable|date|after_or_equal:berlaku_mulai',
        ]);

        try {
            $tarifLog = TarifLog::findOrFail($id);
            $tarifLog->update($request->all());

            // Check if this is the latest tarif for this jenis_plg
            $latestTarif = TarifLog::where('id_jenis', $request->id_jenis)
                ->orderBy('berlaku_mulai', 'desc')
                ->first();

            // If this is the latest tarif, update the JenisPlg record
            if ($latestTarif && $latestTarif->id == $id) {
                $jenisPlg = JenisPlg::where('id_jenis', $request->id_jenis)->first();
                if ($jenisPlg) {
                    $jenisPlg->tarifkwh = $request->tarifkwh;
                    $jenisPlg->biayabeban = $request->biayabeban;
                    $jenisPlg->save();

                    Log::info('JenisPlg updated with edited tarif values', [
                        'id_jenis' => $jenisPlg->id_jenis,
                        'tarifkwh' => $jenisPlg->tarifkwh,
                        'biayabeban' => $jenisPlg->biayabeban
                    ]);
                }
            }

            return redirect()->route('tarif.index')
                ->with('success', 'Tarif berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating tarif: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal memperbarui Tarif: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete record
    public function destroy($id)
    {
        try {
            $tarifLog = TarifLog::findOrFail($id);
            $id_jenis = $tarifLog->id_jenis;
            $tarifLog->delete();

            // After deletion, find the latest tarif for this jenis_plg and update the JenisPlg record
            $latestTarif = TarifLog::where('id_jenis', $id_jenis)
                ->orderBy('berlaku_mulai', 'desc')
                ->first();

            $jenisPlg = JenisPlg::where('id_jenis', $id_jenis)->first();
            if ($jenisPlg) {
                if ($latestTarif) {
                    // Update with the latest tarif values
                    $jenisPlg->tarifkwh = $latestTarif->tarifkwh;
                    $jenisPlg->biayabeban = $latestTarif->biayabeban;
                } else {
                    // No tarif records left, set to default values
                    $jenisPlg->tarifkwh = 0;
                    $jenisPlg->biayabeban = 0;
                }
                $jenisPlg->save();

                Log::info('JenisPlg updated after tarif deletion', [
                    'id_jenis' => $jenisPlg->id_jenis,
                    'tarifkwh' => $jenisPlg->tarifkwh,
                    'biayabeban' => $jenisPlg->biayabeban
                ]);
            }

            return redirect()->route('tarif.index')
                ->with('success', 'Tarif berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting tarif: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Gagal menghapus Tarif: ' . $e->getMessage());
        }
    }
}
