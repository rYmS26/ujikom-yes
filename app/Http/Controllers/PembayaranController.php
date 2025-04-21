<?php

namespace App\Http\Controllers;

use App\Models\Pemakaian;
use App\Models\Transaction;
use App\Models\Pelanggan;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('NoKontrol', 'like', '%' . $request->search . '%')
                  ->orWhere('nama', 'like', '%' . $request->search . '%');
        }

        $pelanggans = $query->latest()->paginate(5);

        return view('pembayaran.index', compact('pelanggans'));
    }

    public function create()
    {
        $pelanggans = Pelanggan::all();
        $petugas = User::all();
        return view('pembayaran.create', compact('pelanggans', 'petugas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'petugas_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'bulan_tagihan' => 'required|string|max:20',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'status' => 'required|in:Lunas,Belum bayar',
            'catatan' => 'nullable|string',
        ]);

        Transaction::create($request->all());

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil disimpan.');
    }

    public function show($noKontrol)
    {
        // Find the customer by NoKontrol
        $pelanggan = Pelanggan::where('NoKontrol', $noKontrol)->firstOrFail();

        // Get all usage records for this customer
        $pemakaians = Pemakaian::where('NoKontrol', $noKontrol)
                              ->orderBy('tahun', 'desc')
                              ->orderBy('bulan', 'desc')
                              ->get();

        // Calculate total unpaid amount
        $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')
                                     ->sum('jumlahbayar');

        return view('pembayaran.show', compact('pelanggan', 'pemakaians', 'totalBelumBayar'));
    }

    public function generatePdf($noKontrol)
    {
        // Find the customer by NoKontrol
        $pelanggan = Pelanggan::where('NoKontrol', $noKontrol)->firstOrFail();

        // Get all usage records for this customer
        $pemakaians = Pemakaian::where('NoKontrol', $noKontrol)
                              ->orderBy('tahun', 'desc')
                              ->orderBy('bulan', 'desc')
                              ->get();

        // Calculate total unpaid amount
        $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')
                                     ->sum('jumlahbayar');

        // Calculate total paid amount
        $totalSudahBayar = $pemakaians->where('status', 'Lunas')
                                     ->sum('jumlahbayar');

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        // Generate PDF using app container
        $pdf = app('dompdf.wrapper');
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pembayaran.pdf', compact(
      'pelanggan',
     'pemakaians',
                'totalBelumBayar',
                'totalSudahBayar',
                'companyInfo'
        ));


        // Set paper size to A4
        $pdf->setPaper('a4', 'portrait');

        // Generate a filename
        $filename = 'Pembayaran_' . $pelanggan->NoKontrol . '_' . date('Ymd') . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    public function generateReceiptPdf($id)
    {
        // Find the payment record
        $pemakaian = Pemakaian::findOrFail($id);

        // Make sure it's a paid record
        if ($pemakaian->status !== 'Lunas') {
            return redirect()->back()->with('error', 'Hanya pembayaran yang sudah lunas yang dapat dicetak.');
        }

        // Get the customer data
        $pelanggan = Pelanggan::where('NoKontrol', $pemakaian->NoKontrol)->firstOrFail();

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        // Generate receipt number
        $receiptNumber = 'INV-' . date('Ym', strtotime($pemakaian->tanggal_bayar)) . '-' . $pemakaian->id;

        // Generate PDF using app container
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pembayaran.receipt', compact(
            'pemakaian',
            'pelanggan',
            'companyInfo',
            'receiptNumber'
        ));

        // Set paper size to A5 for receipt
        $pdf->setPaper('a5', 'portrait');

        // Generate a filename
        $filename = 'Struk_' . $pelanggan->NoKontrol . '_' . date('Ymd', strtotime($pemakaian->tanggal_bayar)) . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    public function processPayment(Request $request, $id)
    {
        $pemakaian = Pemakaian::findOrFail($id);

        $request->validate([
            'jumlah_dibayar' => 'required|numeric|min:' . $pemakaian->jumlahbayar,
        ]);

        // Update the payment status
        $pemakaian->status = 'Lunas';
        $pemakaian->tanggal_bayar = now();
        $pemakaian->save();

        return redirect()->route('pembayaran.show', $pemakaian->NoKontrol)
                         ->with('success', 'Pembayaran berhasil diproses.');
    }

    // New method for processing multiple payments
    public function processMultiplePayments(Request $request, $noKontrol)
    {
        // Validate the request
        $request->validate([
            'pemakaian_ids' => 'required|array',
            'pemakaian_ids.*' => 'exists:pemakaians,id',
            'jumlah_dibayar' => 'required|numeric|min:1',
        ]);

        // Get all selected pemakaian records
        $pemakaianIds = $request->pemakaian_ids;
        $pemakaians = Pemakaian::whereIn('id', $pemakaianIds)->get();

        // Calculate total amount to pay
        $totalAmount = $pemakaians->sum('jumlahbayar');

        // Check if payment amount is sufficient
        if ($request->jumlah_dibayar < $totalAmount) {
            return redirect()->back()->with('error', 'Jumlah pembayaran kurang dari total tagihan.');
        }

        // Process payment for each selected month
        foreach ($pemakaians as $pemakaian) {
            $pemakaian->status = 'Lunas';
            $pemakaian->tanggal_bayar = now();
            $pemakaian->save();
        }

        // Calculate change
        $kembalian = $request->jumlah_dibayar - $totalAmount;

        return redirect()->route('pembayaran.show', $noKontrol)
                         ->with('success', 'Pembayaran untuk ' . count($pemakaianIds) . ' bulan berhasil diproses.')
                         ->with('lastTagihan', $totalAmount)
                         ->with('lastDibayar', $request->jumlah_dibayar)
                         ->with('lastKembalian', $kembalian);
    }

    public function generateMultipleReceiptPdf(Request $request, $noKontrol)
    {
        // Validate the request
        $request->validate([
            'pemakaian_ids' => 'required|array',
            'pemakaian_ids.*' => 'exists:pemakaians,id',
        ]);

        // Get all selected pemakaian records
        $pemakaianIds = $request->pemakaian_ids;
        $pemakaians = Pemakaian::whereIn('id', $pemakaianIds)->get();

        // Get the customer data
        $pelanggan = Pelanggan::where('NoKontrol', $noKontrol)->firstOrFail();

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        // Generate receipt number
        $receiptNumber = 'INV-MULTI-' . date('Ymd') . '-' . $pelanggan->NoKontrol;

        // Calculate total amount
        $totalAmount = $pemakaians->sum('jumlahbayar');

        // Generate PDF using app container
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pembayaran.multiple-receipt', compact(
            'pemakaians',
            'pelanggan',
            'companyInfo',
            'receiptNumber',
            'totalAmount'
        ));

        // Set paper size to A5 for receipt
        $pdf->setPaper('a5', 'portrait');

        // Generate a filename
        $filename = 'Struk_Multiple_' . $pelanggan->NoKontrol . '_' . date('Ymd') . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'pelanggan_id' => 'required|exists:pelanggans,id',
            'petugas_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'bulan_tagihan' => 'required|string|max:20',
            'jumlah_tagihan' => 'required|numeric|min:0',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_pembayaran' => 'required|string|max:50',
            'catatan' => 'nullable|string',
        ]);

        $pembayaran = Transaction::findOrFail($id);
        $pembayaran->update($request->all());

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pembayaran = Transaction::findOrFail($id);
        $pembayaran->delete();

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil dihapus.');
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $pembayaran = Transaction::with(['pelanggan', 'petugas'])
            ->where('bulan_tagihan', 'LIKE', "%{$search}%")
            ->orWhere('jumlah_tagihan', 'LIKE', "%{$search}%")
            ->orWhere('jumlah_bayar', 'LIKE', "%{$search}%")
            ->paginate(10);

        return view('pembayaran.index', compact('pembayaran'));
    }
}
