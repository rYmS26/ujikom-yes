@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pemakaian</h1>
    <form action="{{ route('pemakaian.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nokontrol" class="block text-sm font-medium text-gray-700">No Kontrol</label>
            <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="nokontrol" name="nokontrol" required>
                <option value="" disabled selected>Pilih No Kontrol</option>
                @foreach($pelanggans as $pelanggan)
                    <option value="{{ $pelanggan->NoKontrol }}">{{ $pelanggan->NoKontrol }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
            <input type="number" class="mt-1 block w-full" id="tahun" name="tahun" required>
        </div>

        <div class="mb-4">
            <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
            <input type="number" class="mt-1 block w-full" id="bulan" name="bulan" required>
        </div>

        <div class="mb-4">
            <label for="meterawal" class="block text-sm font-medium text-gray-700">Meter Awal</label>
            <input type="number" class="mt-1 block w-full" id="meterawal" name="meterawal" required>
        </div>

        <div class="mb-4">
            <label for="meterakhir" class="block text-sm font-medium text-gray-700">Meter Akhir</label>
            <input type="number" class="mt-1 block w-full" id="meterakhir" name="meterakhir" required>
        </div>

        <div class="mb-4">
            <label for="jumlahpakai" class="block text-sm font-medium text-gray-700">Jumlah Pakai</label>
            <input type="number" class="mt-1 block w-full" id="jumlahpakai" name="jumlahpakai" readonly>
        </div>

        <div class="mb-4">
            <label for="biayabebanpemakai" class="block text-sm font-medium text-gray-700">Biaya Beban Pemakai</label>
            <input type="number" step="0.01" class="mt-1 block w-full" id="biayabebanpemakai" name="biayabebanpemakai" required readonly>
        </div>

        <div class="mb-4">
            <label for="biayapemakaian" class="block text-sm font-medium text-gray-700">Biaya Pemakaian</label>
            <input type="number" step="0.01" class="mt-1 block w-full" id="biayapemakaian" name="biayapemakaian" required>
        </div>

        <div class="mb-4">
            <label for="totalbiaya" class="block text-sm font-medium text-gray-700">Total Biaya</label>
            <input type="number" step="0.01" class="mt-1 block w-full" id="totalbiaya" name="totalbiaya" required readonly>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select class="mt-1 block w-full" id="status" name="status" required>
                <option value="" disabled selected>Pilih Status</option>
                <option value="Lunas">Lunas</option>
                <option value="Belum Lunas">Belum Lunas</option>
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Simpan</button>
    </form>
</div>

<script>
    function calculateJumlahPakai() {
        const awal = parseFloat(document.getElementById('meterawal').value) || 0;
        const akhir = parseFloat(document.getElementById('meterakhir').value) || 0;
        const jumlah = akhir - awal;
        document.getElementById('jumlahpakai').value = jumlah > 0 ? jumlah : 0;
    }

    function calculateTotal() {
        const biayaBeban = parseFloat(document.getElementById('biayabebanpemakai').value) || 0;
        const biayaPakai = parseFloat(document.getElementById('biayapemakaian').value) || 0;
        document.getElementById('totalbiaya').value = biayaBeban + biayaPakai;
    }

    document.getElementById('meterawal').addEventListener('input', calculateJumlahPakai);
    document.getElementById('meterakhir').addEventListener('input', calculateJumlahPakai);
    document.getElementById('biayapemakaian').addEventListener('input', calculateTotal);

    document.getElementById('nokontrol').addEventListener('change', function () {
        const noKontrol = this.value;
        fetch(`/get-biaya-beban/${noKontrol}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('biayabebanpemakai').value = data.biaya_beban || 0;
                calculateTotal();
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });
</script>
@endsection
