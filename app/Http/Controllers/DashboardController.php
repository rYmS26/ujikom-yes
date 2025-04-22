<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Pemakaian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and charts.
     *
     * @return \Illuminate\View\View
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

        // Get monthly transactions data for current year
        $currentYear = date('Y');
        $monthlyTransactions = $this->getMonthlyTransactions($currentYear);
        $monthlyPayments = $this->getMonthlyPayments($currentYear);
        $monthlyUsage = $this->getMonthlyUsage($currentYear);

        // Get payment status data
        $paymentStatus = [
            'paid' => $totalSudahBayar,
            'unpaid' => $totalBelumBayar
        ];

        // Get customer data by type
        $customerData = $this->getCustomerData();

        return view('dashboard', compact(
            'totalPelanggan',
            'totalPemakaian',
            'totalBelumBayar',
            'totalSudahBayar',
            'years',
            'monthlyTransactions',
            'monthlyPayments',
            'monthlyUsage',
            'paymentStatus',
            'customerData'
        ));
    }

    /**
     * Get chart data for AJAX requests.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $type = $request->input('type', 'transactions');

        $monthlyData = [
            'transactions' => $this->getMonthlyTransactions($year),
            'payments' => $this->getMonthlyPayments($year),
            'usage' => $this->getMonthlyUsage($year)
        ];

        // Get payment status data for the selected year
        $paymentStatus = [
            'paid' => Pemakaian::where('tahun', $year)
                        ->where('status', 'Lunas')
                        ->sum('jumlahbayar'),
            'unpaid' => Pemakaian::where('tahun', $year)
                        ->where('status', 'Belum Bayar')
                        ->sum('jumlahbayar')
        ];

        // Get customer data
        $customerData = $this->getCustomerData();

        return response()->json([
            'monthlyData' => $monthlyData,
            'paymentStatus' => $paymentStatus,
            'customerData' => $customerData
        ]);
    }

    /**
     * Get monthly transaction totals for a specific year.
     *
     * @param  string  $year
     * @return array
     */
    private function getMonthlyTransactions($year)
    {
        $data = [];

        // Get monthly transaction totals
        $monthlyTotals = Pemakaian::where('tahun', $year)
                        ->select(DB::raw('bulan, SUM(jumlahbayar) as total'))
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get()
                        ->keyBy('bulan');

        // Fill in data for all months
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);
            $data[] = [
                'month' => $month,
                'total' => $monthlyTotals->has($month) ? $monthlyTotals[$month]->total : 0
            ];
        }

        return $data;
    }

    /**
     * Get monthly payment data (paid vs unpaid) for a specific year.
     *
     * @param  string  $year
     * @return array
     */
    private function getMonthlyPayments($year)
    {
        $data = [];

        // Get monthly paid amounts
        $monthlyPaid = Pemakaian::where('tahun', $year)
                        ->where('status', 'Lunas')
                        ->select(DB::raw('bulan, SUM(jumlahbayar) as total'))
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get()
                        ->keyBy('bulan');

        // Get monthly unpaid amounts
        $monthlyUnpaid = Pemakaian::where('tahun', $year)
                        ->where('status', 'Belum Bayar')
                        ->select(DB::raw('bulan, SUM(jumlahbayar) as total'))
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get()
                        ->keyBy('bulan');

        // Fill in data for all months
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);
            $data[] = [
                'month' => $month,
                'paid' => $monthlyPaid->has($month) ? $monthlyPaid[$month]->total : 0,
                'unpaid' => $monthlyUnpaid->has($month) ? $monthlyUnpaid[$month]->total : 0
            ];
        }

        return $data;
    }

    /**
     * Get monthly electricity usage data for a specific year.
     *
     * @param  string  $year
     * @return array
     */
    private function getMonthlyUsage($year)
    {
        $data = [];

        // Get monthly usage totals
        $monthlyUsage = Pemakaian::where('tahun', $year)
                        ->select(DB::raw('bulan, SUM(jumlahpakai) as total'))
                        ->groupBy('bulan')
                        ->orderBy('bulan')
                        ->get()
                        ->keyBy('bulan');

        // Fill in data for all months
        for ($i = 1; $i <= 12; $i++) {
            $month = sprintf('%02d', $i);
            $data[] = [
                'month' => $month,
                'usage' => $monthlyUsage->has($month) ? $monthlyUsage[$month]->total : 0
            ];
        }

        return $data;
    }

    /**
     * Get customer distribution data by customer type.
     *
     * @return array
     */
    private function getCustomerData()
    {
    try {
        // Try to get customer counts by type with join
        return DB::table('pelanggans')
                ->join('jenis_pelanggan', 'pelanggans.jenis_plg', '=', 'jenis_pelanggan.id')
                ->select('jenis_pelanggan.nama_jenis as jenis', DB::raw('COUNT(*) as count'))
                ->groupBy('jenis_pelanggan.nama_jenis')
                ->orderBy('count', 'desc')
                ->get()
                ->toArray();
    } catch (\Exception $e) {
        // If there's an error (like missing table), return simple customer count by jenis_plg
        try {
            return DB::table('pelanggans')
                    ->select('jenis_plg as jenis', DB::raw('COUNT(*) as count'))
                    ->groupBy('jenis_plg')
                    ->orderBy('count', 'desc')
                    ->get()
                    ->toArray();
        } catch (\Exception $e) {
            // If that also fails, return empty array
            return [];
        }
    }
}
}
