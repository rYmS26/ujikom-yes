<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pembayaran</title>
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
        .customer-info {
            margin-bottom: 20px;
        }
        .customer-info h2 {
            font-size: 14px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            width: 150px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-row {
            font-weight: bold;
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

    <div class="customer-info">
        <h2>INFORMASI PELANGGAN</h2>
        <div class="info-row">
            <div class="info-label">No Kontrol</div>
            <div>: {{ $pelanggan->NoKontrol }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Nama</div>
            <div>: {{ $pelanggan->nama }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Alamat</div>
            <div>: {{ $pelanggan->alamat }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Jenis Pelanggan</div>
            <div>: {{ $pelanggan->jenisPlg->nama_jenis ?? 'Tidak tersedia' }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Biaya Beban</div>
            <div>: Rp {{ number_format($pelanggan->jenisPlg->biayabeban ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <h2>RIWAYAT PEMAKAIAN & PEMBAYARAN</h2>
    <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>

    <table>
        <thead>
            <tr>
                <th>Periode</th>
                <th>Meter Awal</th>
                <th>Meter Akhir</th>
                <th>Jumlah Pakai</th>
                <th>Biaya Beban</th>
                <th>Biaya Pemakaian</th>
                <th>Total Bayar</th>
                <th>Status</th>
                <th>Tanggal Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemakaians as $pemakaian)
            <tr>
                <td>{{ $pemakaian->bulan }}/{{ $pemakaian->tahun }}</td>
                <td>{{ $pemakaian->meterawal }}</td>
                <td>{{ $pemakaian->meterakhir }}</td>
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
                <td colspan="9" style="text-align: center;">Tidak ada data pemakaian.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Belum Bayar:</td>
                <td colspan="3">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</td>
            </tr>
            <tr class="total-row">
                <td colspan="6" style="text-align: right;">Total Sudah Bayar:</td>
                <td colspan="3">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan.</p>
        <p>© {{ date('Y') }} {{ $companyInfo['name'] }} - Semua Hak Dilindungi</p>
    </div>
</body>
</html>
