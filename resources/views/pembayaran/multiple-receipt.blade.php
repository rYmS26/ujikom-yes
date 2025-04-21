<!-- resources/views/pembayaran/multiple-receipt.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran Multiple</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
        }
        .info {
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 5px;
            text-align: left;
        }
        .table th {
            background-color: #f2f2f2;
        }
        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 10px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ $companyInfo['name'] }}</div>
        <div>{{ $companyInfo['address'] }}</div>
        <div>{{ $companyInfo['phone'] }} | {{ $companyInfo['email'] }}</div>
    </div>

    <div class="info">
        <div class="info-row">
            <div><strong>No. Struk:</strong> {{ $receiptNumber }}</div>
            <div><strong>Tanggal:</strong> {{ date('d/m/Y') }}</div>
        </div>
        <div class="info-row">
            <div><strong>No. Kontrol:</strong> {{ $pelanggan->NoKontrol }}</div>
        </div>
        <div class="info-row">
            <div><strong>Nama:</strong> {{ $pelanggan->nama }}</div>
        </div>
        <div class="info-row">
            <div><strong>Alamat:</strong> {{ $pelanggan->alamat }}</div>
        </div>
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Periode</th>
                <th>Meter Awal</th>
                <th>Meter Akhir</th>
                <th>Jumlah Pakai</th>
                <th>Jumlah Bayar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pemakaians as $index => $pemakaian)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pemakaian->bulan }}/{{ $pemakaian->tahun }}</td>
                <td>{{ $pemakaian->meterawal }}</td>
                <td>{{ $pemakaian->meterakhir }}</td>
                <td>{{ $pemakaian->jumlahpakai }}</td>
                <td>Rp {{ number_format($pemakaian->jumlahbayar, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        <div><strong>Total Pembayaran:</strong> Rp {{ number_format($totalAmount, 0, ',', '.') }}</div>
    </div>

    <div class="footer">
        <p>Terima kasih atas pembayaran Anda.</p>
        <p>{{ $companyInfo['website'] }}</p>
    </div>
</body>
</html>
