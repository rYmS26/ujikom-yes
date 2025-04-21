<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tahunan {{ $year }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
        }
        .report-title {
            text-align: center;
            margin-bottom: 20px;
        }
        .report-title h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .summary {
            margin-bottom: 20px;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .summary-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9f9f9;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $companyInfo['name'] }}</h1>
        <p>{{ $companyInfo['address'] }}</p>
        <p>Telp: {{ $companyInfo['phone'] }} | Email: {{ $companyInfo['email'] }}</p>
        <p>{{ $companyInfo['website'] }}</p>
    </div>

    <div class="report-title">
        <h2>LAPORAN TAHUNAN</h2>
        <p>Tahun: {{ $year }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <table class="summary-table">
            <tr>
                <th>Total Pemakaian</th>
                <th>Total Biaya Beban</th>
                <th>Total Biaya Pemakaian</th>
                <th>Total Belum Bayar</th>
                <th>Total Sudah Bayar</th>
            </tr>
            <tr>
                <td>{{ number_format($yearlyTotals['total_pemakaian']) }} m³</td>
                <td>Rp {{ number_format($yearlyTotals['total_biaya_beban'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_biaya_pemakaian'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_belum_bayar'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_sudah_bayar'], 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <h3>Ringkasan Bulanan</h3>

    <table>
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Pelanggan</th>
                <th>Total Pemakaian</th>
                <th>Biaya Beban</th>
                <th>Biaya Pemakaian</th>
                <th>Total Tagihan</th>
                <th>Belum Bayar</th>
                <th>Sudah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlySummaries as $summary)
            <tr>
                <td>{{ $summary['month_name'] }}</td>
                <td>{{ number_format($summary['jumlah_pelanggan']) }}</td>
                <td>{{ number_format($summary['total_pemakaian']) }} m³</td>
                <td>Rp {{ number_format($summary['total_biaya_beban'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['total_biaya_pemakaian'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['total_jumlah_bayar'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['total_belum_bayar'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['total_sudah_bayar'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td>Total</td>
                <td>-</td>
                <td>{{ number_format($yearlyTotals['total_pemakaian']) }} m³</td>
                <td>Rp {{ number_format($yearlyTotals['total_biaya_beban'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_biaya_pemakaian'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_jumlah_bayar'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_belum_bayar'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($yearlyTotals['total_sudah_bayar'], 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan.</p>
        <p>© {{ date('Y') }} {{ $companyInfo['name'] }} - Semua Hak Dilindungi</p>
    </div>
</body>
</html>
