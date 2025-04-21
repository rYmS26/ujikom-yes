@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Data Pemakaian</h1>

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <form action="{{ route('pemakaian.store') }}" method="POST">
        @csrf
        <!-- No Kontrol -->
        <div class="mb-4">
            <label for="NoKontrol" class="block text-sm font-medium text-gray-700">No Kontrol</label>

            <!-- Search Input -->
            <div class="relative mb-2">
                <input
                    type="text"
                    id="searchPelanggan"
                    placeholder="Cari berdasarkan No Kontrol atau Nama..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10"
                >
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <select name="NoKontrol" id="NoKontrol" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="" selected disabled>Pilih No Kontrol</option>
                @foreach ($pelanggans as $pelanggan)
                    <option
                        value="{{ $pelanggan->NoKontrol }}"
                        data-biaya-beban="{{ $pelanggan->jenisPlg->biayabeban ?? 0 }}"
                        data-search-text="{{ strtolower($pelanggan->NoKontrol . ' ' . $pelanggan->nama) }}"
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
                    <option value="{{ $i }}">{{ $i }}</option>
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
                    <option value="{{ $num }}" data-month-name="{{ $name }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Pesan Peringatan untuk Bulan yang Sudah Dibayar -->
        <div id="bulanAlert" class="mb-4 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded hidden">
            <p>Bulan yang dipilih sudah dibayar. Silakan pilih bulan lain.</p>
        </div>

        <!-- Meter Awal -->
        <div class="mb-4">
            <label for="meterawal" class="block text-sm font-medium text-gray-700">Meter Awal</label>
            <input type="number" name="meterawal" id="meterawal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Meter Akhir -->
        <div class="mb-4">
            <label for="meterakhir" class="block text-sm font-medium text-gray-700">Meter Akhir</label>
            <input type="number" name="meterakhir" id="meterakhir" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
        </div>

        <!-- Jumlah Pakai -->
        <div class="mb-4">
            <label for="jumlahpakai" class="block text-sm font-medium text-gray-700">Jumlah Pakai</label>
            <input type="number" name="jumlahpakai" id="jumlahpakai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Biaya Beban -->
        <div class="mb-4">
            <label for="biayabebanpemakai" class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <input type="number" name="biayabebanpemakai" id="biayabebanpemakai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Biaya Pemakaian -->
        <div class="mb-4">
            <label for="biayapemakaian" class="block text-sm font-medium text-gray-700">Biaya Pemakaian</label>
            <input type="number" name="biayapemakaian" id="biayapemakaian" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Status -->
        <div class="mb-4">
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <input type="text" name="status" id="status" value="Belum Bayar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-gray-100" readonly>
        </div>

        <!-- Jumlah Bayar -->
        <div class="mb-4">
            <label for="jumlahbayar" class="block text-sm font-medium text-gray-700">Jumlah Bayar</label>
            <input type="number" name="jumlahbayar" id="jumlahbayar" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" readonly>
        </div>

        <!-- Tombol Submit -->
        <button type="submit" id="submitButton" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600">Simpan</button>
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
    const tahunSelect = document.getElementById('tahun');
    const bulanSelect = document.getElementById('bulan');
    const bulanAlert = document.getElementById('bulanAlert');
    const submitButton = document.getElementById('submitButton');

    // Menyimpan data bulan yang sudah dibayar
    let paidMonths = {};

    // Tambahkan kode ini di bagian <script> setelah deklarasi variabel
    const searchInput = document.getElementById('searchPelanggan');

    // Fungsi untuk mencari pelanggan
    function searchPelanggan() {
        const searchText = searchInput.value.toLowerCase();
        const options = noKontrolSelect.options;

        // Reset tampilan semua opsi
        for (let i = 0; i < options.length; i++) {
            options[i].style.display = '';
        }

        // Jika tidak ada teks pencarian, keluar dari fungsi
        if (!searchText) return;

        // Buat array untuk menyimpan opsi yang cocok
        const matchingOptions = [];

        // Loop melalui semua opsi
        for (let i = 0; i < options.length; i++) {
            const option = options[i];

            // Lewati opsi pertama (placeholder)
            if (i === 0) continue;

            // Ambil teks pencarian dari atribut data-search-text
            const optionSearchText = option.getAttribute('data-search-text');

            // Jika teks pencarian cocok, tambahkan ke array
            if (optionSearchText && optionSearchText.includes(searchText)) {
                matchingOptions.push(option);
            }
        }

        // Jika ada opsi yang cocok, pilih opsi pertama yang cocok
        if (matchingOptions.length > 0) {
            noKontrolSelect.value = matchingOptions[0].value;

            // Trigger event change untuk memperbarui form
            const event = new Event('change');
            noKontrolSelect.dispatchEvent(event);
        }
    }

    // Event listener untuk input pencarian
    searchInput.addEventListener('input', searchPelanggan);

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
        const tarifPerKwh = 1500; // misalnya
        const biayaPemakaian = jumlahPakai * tarifPerKwh;
        biayaPemakaianInput.value = biayaPemakaian.toFixed(2);
        jumlahBayarInput.value = (biayaPemakaian + biayaBeban).toFixed(2);
    }

    // Fungsi untuk memeriksa bulan yang sudah dibayar
    function checkPaidMonths() {
        const noKontrol = noKontrolSelect.value;

        if (!noKontrol) return;

        // Reset status bulan
        resetMonthOptions();

        // Ambil data bulan yang sudah dibayar
        fetch(`/check-paid-months/${noKontrol}`)
            .then(response => response.json())
            .then(data => {
                // Simpan data bulan yang sudah dibayar
                paidMonths = data;

                // Perbarui tampilan bulan
                updateMonthDisplay();
            })
            .catch(error => {
                console.error('Error fetching paid months:', error);
            });
    }

    // Fungsi untuk memperbarui tampilan bulan
    function updateMonthDisplay() {
        const tahun = tahunSelect.value;
        const options = bulanSelect.options;

        // Reset semua opsi bulan
        resetMonthOptions();

        // Jika tidak ada data untuk tahun ini, keluar dari fungsi
        if (!paidMonths[tahun]) return;

        // Loop melalui semua opsi bulan
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const bulan = option.value;

            // Jika bulan sudah dibayar, tandai sebagai disabled
            if (paidMonths[tahun].includes(bulan)) {
                option.disabled = true;
                option.classList.add('text-gray-400');
                option.text = `${option.getAttribute('data-month-name')} (Sudah Dibayar)`;
            }
        }

        // Pilih bulan pertama yang belum dibayar
        selectFirstUnpaidMonth();

        // Periksa bulan yang dipilih saat ini
        checkCurrentMonth();
    }

    // Fungsi untuk memilih bulan pertama yang belum dibayar
    function selectFirstUnpaidMonth() {
        const options = bulanSelect.options;
        let found = false;

        // Loop melalui semua opsi bulan
        for (let i = 0; i < options.length; i++) {
            const option = options[i];

            // Jika bulan belum dibayar, pilih bulan tersebut
            if (!option.disabled) {
                bulanSelect.selectedIndex = i;
                found = true;
                break;
            }
        }

        // Jika semua bulan sudah dibayar, tampilkan pesan
        if (!found) {
            bulanAlert.textContent = "Semua bulan pada tahun ini sudah dibayar. Silakan pilih tahun lain.";
            bulanAlert.classList.remove('hidden');
            submitButton.disabled = true;
        }
    }

    // Fungsi untuk mereset status bulan
    function resetMonthOptions() {
        const options = bulanSelect.options;

        // Loop melalui semua opsi bulan
        for (let i = 0; i < options.length; i++) {
            const option = options[i];
            const monthName = option.getAttribute('data-month-name');

            // Reset status bulan
            option.disabled = false;
            option.classList.remove('text-gray-400');
            option.text = monthName;
        }

        // Sembunyikan peringatan
        bulanAlert.classList.add('hidden');
        submitButton.disabled = false;
    }

    // Fungsi untuk memeriksa bulan yang dipilih saat ini
    function checkCurrentMonth() {
        const tahun = tahunSelect.value;
        const bulan = bulanSelect.value;

        // Jika tidak ada data untuk tahun ini, keluar dari fungsi
        if (!paidMonths[tahun]) return;

        // Periksa apakah bulan yang dipilih sudah dibayar
        if (paidMonths[tahun].includes(bulan)) {
            // Tampilkan peringatan
            bulanAlert.textContent = "Bulan yang dipilih sudah dibayar. Silakan pilih bulan lain.";
            bulanAlert.classList.remove('hidden');
            submitButton.disabled = true;
        } else {
            // Sembunyikan peringatan
            bulanAlert.classList.add('hidden');
            submitButton.disabled = false;
        }
    }

    // Event listeners
    meterAwalInput.addEventListener('input', calculateJumlahPakai);
    meterAkhirInput.addEventListener('input', calculateJumlahPakai);

    noKontrolSelect.addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const biayaBeban = parseFloat(selectedOption.getAttribute('data-biaya-beban')) || 0;
        biayaBebanInput.value = biayaBeban.toFixed(2);
        calculateTotalBiaya();

        // Periksa bulan yang sudah dibayar
        checkPaidMonths();
    });

    tahunSelect.addEventListener('change', function() {
        // Perbarui tampilan bulan
        updateMonthDisplay();
    });

    bulanSelect.addEventListener('change', function() {
        // Periksa bulan yang dipilih saat ini
        checkCurrentMonth();
    });

    // Inisialisasi form
    document.addEventListener('DOMContentLoaded', function() {
        // Jika ada pelanggan yang dipilih, periksa bulan yang sudah dibayar
        if (noKontrolSelect.value) {
            checkPaidMonths();
        }
    });

    // Live search functionality
    document.getElementById('searchPelanggan').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const options = document.querySelectorAll('#NoKontrol option');

        options.forEach(option => {
            const searchText = option.getAttribute('data-search-text');
            if (searchText && searchText.includes(searchTerm)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
    });
</script>
@endsection
