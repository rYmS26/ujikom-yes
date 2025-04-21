<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Display the report dashboard/index page
     */
    public function index()
    {
        // Get summary statistics
        $totalPelanggan = Pelanggan::count();
        $totalPemakaian = Pemakaian::count();
        $totalBelumBayar = Pemakaian::where('status', 'Belum Bayar')->sum('jumlahbayar');
        $totalSudahBayar = Pemakaian::where('status', 'Lunas')->sum('jumlahbayar');

        // Get years for dropdown
        $years = Pemakaian::select(DB::raw('DISTINCT tahun'))
                        ->orderBy('tahun', 'desc')
                        ->pluck('tahun');

        // Get months for dropdown (1-12)
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return view('laporan.index', compact(
            'totalPelanggan',
            'totalPemakaian',
            'totalBelumBayar',
            'totalSudahBayar',
            'years',
            'months'
        ));
    }

    /**
     * Generate monthly report
     */
    public function monthly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string',
        ]);

        $year = $request->year;
        $month = $request->month;

        // Get all transactions for the selected month
        $pemakaians = Pemakaian::where('tahun', $year)
                              ->where('bulan', $month)
                              ->orderBy('NoKontrol')
                              ->get();

        // Calculate totals
        $totalPemakaian = $pemakaians->sum('jumlahpakai');
        $totalBiayaBeban = $pemakaians->sum('biayabebanpemakai');
        $totalBiayaPemakaian = $pemakaians->sum('biayapemakaian');
        $totalJumlahBayar = $pemakaians->sum('jumlahbayar');
        $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')->sum('jumlahbayar');
        $totalSudahBayar = $pemakaians->where('status', 'Lunas')->sum('jumlahbayar');

        // Get month name
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];
        $monthName = $months[$month] ?? $month;

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        return view('laporan.monthly', compact(
            'pemakaians',
            'year',
            'month',
            'monthName',
            'totalPemakaian',
            'totalBiayaBeban',
            'totalBiayaPemakaian',
            'totalJumlahBayar',
            'totalBelumBayar',
            'totalSudahBayar',
            'companyInfo'
        ));
    }

    /**
     * Generate yearly report
     */
    public function yearly(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
        ]);

        $year = $request->year;

        // Get monthly summaries for the selected year
        $monthlySummaries = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);

            $pemakaians = Pemakaian::where('tahun', $year)
                                  ->where('bulan', $month)
                                  ->get();

            $monthlySummaries[] = [
                'month' => $month,
                'month_name' => $this->getMonthName($month),
                'total_pemakaian' => $pemakaians->sum('jumlahpakai'),
                'total_biaya_beban' => $pemakaians->sum('biayabebanpemakai'),
                'total_biaya_pemakaian' => $pemakaians->sum('biayapemakaian'),
                'total_jumlah_bayar' => $pemakaians->sum('jumlahbayar'),
                'total_belum_bayar' => $pemakaians->where('status', 'Belum Bayar')->sum('jumlahbayar'),
                'total_sudah_bayar' => $pemakaians->where('status', 'Lunas')->sum('jumlahbayar'),
                'jumlah_pelanggan' => $pemakaians->count(),
            ];
        }

        // Calculate yearly totals
        $yearlyTotals = [
            'total_pemakaian' => array_sum(array_column($monthlySummaries, 'total_pemakaian')),
            'total_biaya_beban' => array_sum(array_column($monthlySummaries, 'total_biaya_beban')),
            'total_biaya_pemakaian' => array_sum(array_column($monthlySummaries, 'total_biaya_pemakaian')),
            'total_jumlah_bayar' => array_sum(array_column($monthlySummaries, 'total_jumlah_bayar')),
            'total_belum_bayar' => array_sum(array_column($monthlySummaries, 'total_belum_bayar')),
            'total_sudah_bayar' => array_sum(array_column($monthlySummaries, 'total_sudah_bayar')),
        ];

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        return view('laporan.yearly', compact(
            'year',
            'monthlySummaries',
            'yearlyTotals',
            'companyInfo'
        ));
    }

    /**
     * Generate custom date range report
     */
    /**
 * Generate custom date range report
 */
public function dateRange(Request $request)
{
    $request->validate([
        'start_date' => 'required|date',
        'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $startDate = Carbon::parse($request->start_date);
    $endDate = Carbon::parse($request->end_date);

    // Perbaikan query untuk SQLite
    $pemakaians = Pemakaian::whereRaw("(tahun || '-' || bulan || '-01') BETWEEN ? AND ?", [
                        $startDate->format('Y-m-d'),
                        $endDate->format('Y-m-d')
                    ])
                    ->orderBy('tahun')
                    ->orderBy('bulan')
                    ->orderBy('NoKontrol')
                    ->get();

    // Calculate totals
    $totalPemakaian = $pemakaians->sum('jumlahpakai');
    $totalBiayaBeban = $pemakaians->sum('biayabebanpemakai');
    $totalBiayaPemakaian = $pemakaians->sum('biayapemakaian');
    $totalJumlahBayar = $pemakaians->sum('jumlahbayar');
    $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')->sum('jumlahbayar');
    $totalSudahBayar = $pemakaians->where('status', 'Lunas')->sum('jumlahbayar');

    // Get company information
    $companyInfo = [
        'name' => 'PLN INDONESIA',
        'address' => 'Jl. Pemuda No. 123, Jakarta',
        'phone' => '(021) 1234-5678',
        'email' => 'info@PLN.co.id',
        'website' => 'www.PLN.co.id'
    ];

    return view('laporan.date-range', compact(
        'pemakaians',
        'startDate',
        'endDate',
        'totalPemakaian',
        'totalBiayaBeban',
        'totalBiayaPemakaian',
        'totalJumlahBayar',
        'totalBelumBayar',
        'totalSudahBayar',
        'companyInfo'
    ));
}

    /**
     * Generate status report (paid/unpaid)
     */
    public function status(Request $request)
    {
        $request->validate([
            'status' => 'required|in:Lunas,Belum Bayar,all',
            'year' => 'nullable|integer',
            'month' => 'nullable|string',
        ]);

        $status = $request->status;
        $year = $request->year;
        $month = $request->month;

        $query = Pemakaian::query();

        // Filter by status if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by year if provided
        if ($year) {
            $query->where('tahun', $year);
        }

        // Filter by month if provided
        if ($month) {
            $query->where('bulan', $month);
        }

        // Get filtered transactions
        $pemakaians = $query->orderBy('tahun', 'desc')
                          ->orderBy('bulan', 'desc')
                          ->orderBy('NoKontrol')
                          ->get();

        // Calculate totals
        $totalPemakaian = $pemakaians->sum('jumlahpakai');
        $totalBiayaBeban = $pemakaians->sum('biayabebanpemakai');
        $totalBiayaPemakaian = $pemakaians->sum('biayapemakaian');
        $totalJumlahBayar = $pemakaians->sum('jumlahbayar');

        // Get month name if month is provided
        $monthName = null;
        if ($month) {
            $monthName = $this->getMonthName($month);
        }

        // Get company information
        $companyInfo = [
            'name' => 'PLN INDONESIA',
            'address' => 'Jl. Pemuda No. 123, Jakarta',
            'phone' => '(021) 1234-5678',
            'email' => 'info@PLN.co.id',
            'website' => 'www.PLN.co.id'
        ];

        return view('laporan.status', compact(
            'pemakaians',
            'status',
            'year',
            'month',
            'monthName',
            'totalPemakaian',
            'totalBiayaBeban',
            'totalBiayaPemakaian',
            'totalJumlahBayar',
            'companyInfo'
        ));
    }

    /**
     * Generate PDF for monthly report
     */
    public function monthlyPdf(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'month' => 'required|string',
        ]);

        $year = $request->year;
        $month = $request->month;

        // Get all transactions for the selected month
        $pemakaians = Pemakaian::where('tahun', $year)
                              ->where('bulan', $month)
                              ->orderBy('NoKontrol')
                              ->get();

        // Calculate totals
        $totalPemakaian = $pemakaians->sum('jumlahpakai');
        $totalBiayaBeban = $pemakaians->sum('biayabebanpemakai');
        $totalBiayaPemakaian = $pemakaians->sum('biayapemakaian');
        $totalJumlahBayar = $pemakaians->sum('jumlahbayar');
        $totalBelumBayar = $pemakaians->where('status', 'Belum Bayar')->sum('jumlahbayar');
        $totalSudahBayar = $pemakaians->where('status', 'Lunas')->sum('jumlahbayar');

        // Get month name
        $monthName = $this->getMonthName($month);

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
        $pdf->loadView('laporan.pdf.monthly', compact(
            'pemakaians',
            'year',
            'month',
            'monthName',
            'totalPemakaian',
            'totalBiayaBeban',
            'totalBiayaPemakaian',
            'totalJumlahBayar',
            'totalBelumBayar',
            'totalSudahBayar',
            'companyInfo'
        ));

        // Set paper size to A4 landscape for better table display
        $pdf->setPaper('a4', 'landscape');

        // Generate a filename
        $filename = 'Laporan_Bulanan_' . $month . '_' . $year . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    /**
     * Generate PDF for yearly report
     */
    public function yearlyPdf(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
        ]);

        $year = $request->year;

        // Get monthly summaries for the selected year
        $monthlySummaries = [];

        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);

            $pemakaians = Pemakaian::where('tahun', $year)
                                  ->where('bulan', $month)
                                  ->get();

            $monthlySummaries[] = [
                'month' => $month,
                'month_name' => $this->getMonthName($month),
                'total_pemakaian' => $pemakaians->sum('jumlahpakai'),
                'total_biaya_beban' => $pemakaians->sum('biayabebanpemakai'),
                'total_biaya_pemakaian' => $pemakaians->sum('biayapemakaian'),
                'total_jumlah_bayar' => $pemakaians->sum('jumlahbayar'),
                'total_belum_bayar' => $pemakaians->where('status', 'Belum Bayar')->sum('jumlahbayar'),
                'total_sudah_bayar' => $pemakaians->where('status', 'Lunas')->sum('jumlahbayar'),
                'jumlah_pelanggan' => $pemakaians->count(),
            ];
        }

        // Calculate yearly totals
        $yearlyTotals = [
            'total_pemakaian' => array_sum(array_column($monthlySummaries, 'total_pemakaian')),
            'total_biaya_beban' => array_sum(array_column($monthlySummaries, 'total_biaya_beban')),
            'total_biaya_pemakaian' => array_sum(array_column($monthlySummaries, 'total_biaya_pemakaian')),
            'total_jumlah_bayar' => array_sum(array_column($monthlySummaries, 'total_jumlah_bayar')),
            'total_belum_bayar' => array_sum(array_column($monthlySummaries, 'total_belum_bayar')),
            'total_sudah_bayar' => array_sum(array_column($monthlySummaries, 'total_sudah_bayar')),
        ];

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
        $pdf->loadView('laporan.pdf.yearly', compact(
            'year',
            'monthlySummaries',
            'yearlyTotals',
            'companyInfo'
        ));

        // Set paper size to A4 portrait
        $pdf->setPaper('a4', 'portrait');

        // Generate a filename
        $filename = 'Laporan_Tahunan_' . $year . '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    /**
     * Generate PDF for status report
     */
    public function statusPdf(Request $request)
    {
        $request->validate([
            'status' => 'required|in:Lunas,Belum Bayar,all',
            'year' => 'nullable|integer',
            'month' => 'nullable|string',
        ]);

        $status = $request->status;
        $year = $request->year;
        $month = $request->month;

        $query = Pemakaian::query();

        // Filter by status if not 'all'
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by year if provided
        if ($year) {
            $query->where('tahun', $year);
        }

        // Filter by month if provided
        if ($month) {
            $query->where('bulan', $month);
        }

        // Get filtered transactions
        $pemakaians = $query->orderBy('tahun', 'desc')
                          ->orderBy('bulan', 'desc')
                          ->orderBy('NoKontrol')
                          ->get();

        // Calculate totals
        $totalPemakaian = $pemakaians->sum('jumlahpakai');
        $totalBiayaBeban = $pemakaians->sum('biayabebanpemakai');
        $totalBiayaPemakaian = $pemakaians->sum('biayapemakaian');
        $totalJumlahBayar = $pemakaians->sum('jumlahbayar');

        // Get month name if month is provided
        $monthName = null;
        if ($month) {
            $monthName = $this->getMonthName($month);
        }

        // Get status text for title
        $statusText = $status === 'all' ? 'Semua Status' : ($status === 'Lunas' ? 'Sudah Bayar' : 'Belum Bayar');

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
        $pdf->loadView('laporan.pdf.status', compact(
            'pemakaians',
            'status',
            'statusText',
            'year',
            'month',
            'monthName',
            'totalPemakaian',
            'totalBiayaBeban',
            'totalBiayaPemakaian',
            'totalJumlahBayar',
            'companyInfo'
        ));

        // Set paper size to A4 landscape for better table display
        $pdf->setPaper('a4', 'landscape');

        // Generate a filename
        $filename = 'Laporan_Status_' . $status;
        if ($year) {
            $filename .= '_' . $year;
        }
        if ($month) {
            $filename .= '_' . $month;
        }
        $filename .= '.pdf';

        // Download the PDF
        return $pdf->download($filename);
    }

    /**
     * Helper method to get month name from month number
     */
    private function getMonthName($month)
    {
        $months = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return $months[$month] ?? $month;
    }
}
