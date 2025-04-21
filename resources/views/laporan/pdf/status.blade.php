<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Status Pembayaran</title>
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
        .status-lunas {
            color: green;
            font-weight: bold;
        }
        .status-belum {
            color: red;
            font-weight: bold;
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
        <h2>LAPORAN STATUS PEMBAYARAN</h2>
        <p>Status: {{ $statusText }}</p>
        @if($year)
            <p>Tahun: {{ $year }}</p>
        @endif
        @if($month)
            <p>Bulan: {{ $monthName }}</p>
        @endif
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <table class="summary-table">
            <tr>
                <th>Total Pemakaian</th>
                <th>Total Biaya Beban</th>
                <th>Total Biaya Pemakaian</th>
                <th>Total Tagihan</th>
            </tr>
            <tr>
                <td>{{ number_format($totalPemakaian) }} m³</td>
                <td>Rp {{ number_format($totalBiayaBeban, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBiayaPemakaian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalJumlahBayar, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <h3>Detail Transaksi</h3>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>No Kontrol</th>
                <th>Nama Pelanggan</th>
                <th>Jumlah Pakai</th>
                <th>Biaya Beban</th>
                <th>Biaya Pemakaian</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemakaians as $index => $pemakaian)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pemakaian->bulan }}/{{ $pemakaian->tahun }}</td>
                <td>{{ $pemakaian->NoKontrol }}</td>
                <td>{{ $pemakaian->pelanggan->nama ?? 'N/A' }}</td>
                <td>{{ $pemakaian->jumlahpakai }}</td>
                <td>Rp {{ number_format($pemakaian->biayabebanpemakai, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pemakaian->biayapemakaian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($pemakaian->jumlahbayar, 0, ',', '.') }}</td>
                <td class="{{ $pemakaian->status === 'Lunas' ? 'status-lunas' : 'status-belum' }}">
                    {{ $pemakaian->status }}
                </td>
                <td>{{ $pemakaian->tanggal_bayar ? date('d/m/Y', strtotime($pemakaian->tanggal_bayar)) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center;">Tidak ada data untuk kriteria ini</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Total:</td>
                <td>{{ number_format($totalPemakaian) }}</td>
                <td>Rp {{ number_format($totalBiayaBeban, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalBiayaPemakaian, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($totalJumlahBayar, 0, ',', '.') }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan.</p>
        <p>© {{ date('Y') }} {{ $companyInfo['name'] }} - Semua Hak Dilindungi</p>
    </div>
</body>
</html>
