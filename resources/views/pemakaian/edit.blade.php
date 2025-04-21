@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Data Pemakaian</h1>

    @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('pemakaian.update', $pemakaian->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- No Kontrol -->
        <div class="mb-4">
            <label for="NoKontrol" class="block text-sm font-medium text-gray-700">No Kontrol</label>
            <select name="NoKontrol" id="NoKontrol" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @foreach ($pelanggans as $pelanggan)
                    <option
                        value="{{ $pelanggan->NoKontrol }}"
                        data-biaya-beban="{{ $pelanggan->jenisPlg->biayabeban ?? 0 }}"
                        {{ $pemakaian->NoKontrol == $pelanggan->NoKontrol ? 'selected' : '' }}
                    >
                        {{ $pelanggan->NoKontrol }} - {{ $pelanggan->nama }}
                        ({{ $pelanggan->jenisPlg->nama_jenis ?? 'Jenis tidak ditemukan' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Tahun -->
        <div class="mb-4">
            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
            <select name="tahun" id="tahun" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @for ($i = now()->year; $i >= 2000; $i--)
                    <option value="{{ $i }}" {{ $pemakaian->tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                @endfor
            </select>
        </div>

        <!-- Bulan -->
        <div class="mb-4">
            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
            <select name="bulan" id="bulan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                @foreach ([
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ] as $num => $name)
                    <option value="{{ $num }}" {{ $pemakaian->bulan == $num ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Meter Awal -->
        <div class="mb-4">
            <label for="meterawal" class="block text-sm font-medium text-gray-700">Meter Awal</label>
            <input type="number" name="meterawal" id="meterawal" value="{{ $pemakaian->meterawal }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Meter Akhir -->
        <div class="mb-4">
            <label for="meterakhir" class="block text-sm font-medium text-gray-700">Meter Akhir</label>
            <input type="number" name="meterakhir" id="meterakhir" value="{{ $pemakaian->meterakhir }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Jumlah Pakai -->
        <div class="mb-4">
            <label for="jumlahpakai" class="block text-sm font-medium text-gray-700">Jumlah Pakai</label>
            <input type="number" name="jumlahpakai" id="jumlahpakai" value="{{ $pemakaian->jumlahpakai }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Biaya Beban -->
        <div class="mb-4">
            <label for="biayabebanpemakai" class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <input type="number" name="biayabebanpemakai" id="biayabebanpemakai" value="{{ $pemakaian->biayabebanpemakai }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Biaya Pemakaian -->
        <div class="mb-4">
            <label for="biayapemakaian" class="block text-sm font-medium text-gray-700">Biaya Pemakaian</label>
            <input type="number" name="biayapemakaian" id="biayapemakaian" value="{{ $pemakaian->biayapemakaian }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select name="status" id="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="Belum Bayar" {{ $pemakaian->status == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                <option value="Lunas" {{ $pemakaian->status == 'Lunas' ? 'selected' : '' }}>Sudah Bayar</option>
            </select>
        </div>

        <!-- Jumlah Bayar -->
        <div class="mb-4">
            <label for="jumlahbayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
            <input type="number" name="jumlahbayar" id="jumlahbayar" value="{{ $pemakaian->jumlahbayar }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Simpan Perubahan</button>
    </form>
</div>

<script>
    const meterAwalInput = document.getElementById('meterawal');
    const meterAkhirInput = document.getElementById('meterakhir');
    const jumlahPakaiInput = document.getElementById('jumlahpakai');
    const biayaBebanInput = document.getElementById('biayabebanpemakai');
    const biayaPemakaianInput = document.getElementById('biayapemakaian');
    const jumlahBayarInput = document.getElementById('jumlahbayar');
    const noKontrolSelect = document.getElementById('NoKontrol');

    function calculateJumlahPakai() {
        const awal = parseInt(meterAwalInput.value) || 0;
        const akhir = parseInt(meterAkhirInput.value) || 0;
        const jumlah = akhir - awal;
        jumlahPakaiInput.value = jumlah > 0 ? jumlah : 0;
        calculateTotalBiaya();
    }

    function calculateTotalBiaya() {
        const jumlahPakai = parseFloat(jumlahPakaiInput.value) || 0;
        const biayaBeban = parseFloat(biayaBebanInput.value) || 0;
        const tarifPerKwh = 1500; // Tarif per kWh
        const biayaPemakaian = jumlahPakai * tarifPerKwh;
        biayaPemakaianInput.value = biayaPemakaian.toFixed(2);
        jumlahBayarInput.value = (biayaPemakaian + biayaBeban).toFixed(2);
    }

    meterAwalInput.addEventListener('input', calculateJumlahPakai);
    meterAkhirInput.addEventListener('input', calculateJumlahPakai);

    noKontrolSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const biayaBeban = parseFloat(selectedOption.getAttribute('data-biaya-beban')) || 0;
        biayaBebanInput.value = biayaBeban.toFixed(2);
        calculateTotalBiaya();
    });

    document.addEventListener('DOMContentLoaded', calculateJumlahPakai);
</script>
@endsection
