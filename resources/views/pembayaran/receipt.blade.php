<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Struk Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.5;
            color: #333;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px dashed #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        .receipt-details h2 {
            font-size: 14px;
            margin-bottom: 10px;
            text-align: center;
            border-bottom: 1px dashed #ddd;
            padding-bottom: 5px;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .info-label {
            width: 120px;
            font-weight: bold;
        }
        .payment-details {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
        .payment-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .payment-total {
            font-weight: bold;
            font-size: 14px;
            border-top: 1px dashed #ddd;
            padding-top: 5px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px dashed #333;
            padding-top: 10px;
        }
        .thank-you {
            text-align: center;
            font-weight: bold;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="header">
            <h1>{{ $companyInfo['name'] }}</h1>
            <p>{{ $companyInfo['address'] }}</p>
            <p>Telp: {{ $companyInfo['phone'] }}</p>
            <p>{{ $companyInfo['website'] }}</p>
        </div>

        <div class="receipt-details">
            <h2>STRUK PEMBAYARAN</h2>
            <div class="info-row">
                <div class="info-label">No. Struk</div>
                <div>: {{ $receiptNumber }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Tanggal</div>
                <div>: {{ date('d/m/Y H:i', strtotime($pemakaian->tanggal_bayar)) }}</div>
            </div>
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
        </div>

        <div class="payment-details">
            <div class="payment-row">
                <div>Periode</div>
                <div>: {{ $pemakaian->bulan }}/{{ $pemakaian->tahun }}</div>
            </div>
            <div class="payment-row">
                <div>Meter Awal</div>
                <div>: {{ $pemakaian->meterawal }}</div>
            </div>
            <div class="payment-row">
                <div>Meter Akhir</div>
                <div>: {{ $pemakaian->meterakhir }}</div>
            </div>
            <div class="payment-row">
                <div>Jumlah Pakai</div>
                <div>: {{ $pemakaian->jumlahpakai }}</div>
            </div>
            <div class="payment-row">
                <div>Biaya Beban</div>
                <div>: Rp {{ number_format($pemakaian->biayabebanpemakai, 0, ',', '.') }}</div>
            </div>
            <div class="payment-row">
                <div>Biaya Pemakaian</div>
                <div>: Rp {{ number_format($pemakaian->biayapemakaian, 0, ',', '.') }}</div>
            </div>
            <div class="payment-row payment-total">
                <div>TOTAL BAYAR</div>
                <div>: Rp {{ number_format($pemakaian->jumlahbayar, 0, ',', '.') }}</div>
            </div>
        </div>

        <div class="thank-you">
            TERIMA KASIH
        </div>

        <div class="footer">
            <p>Struk ini merupakan bukti pembayaran yang sah.</p>
            <p>© {{ date('Y') }} {{ $companyInfo['name'] }}</p>
        </div>
    </div>
</body>
</html>
