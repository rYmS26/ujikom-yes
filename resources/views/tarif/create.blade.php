@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Tarif</h1>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tarif.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="id_jenis" class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>

            <!-- Input Pencarian untuk Jenis Pelanggan -->
            <div class="relative mb-2">
                <input
                    type="text"
                    id="searchJenisPelanggan"
                    placeholder="Cari jenis pelanggan..."
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 pl-10"
                >
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Dropdown Jenis Pelanggan -->
            <select name="id_jenis" id="id_jenis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Pilih Jenis Pelanggan</option>
                @foreach($jenisPelanggan as $jenis)
                    <option
                        value="{{ $jenis->id_jenis }}"
                        {{ old('id_jenis') == $jenis->id_jenis ? 'selected' : '' }}
                        data-search-text="{{ strtolower($jenis->id_jenis . ' ' . $jenis->nama_jenis) }}"
                    >
                        {{ $jenis->id_jenis }} - {{ $jenis->nama_jenis }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="tarifkwh" class="block text-sm font-medium text-gray-700">Tarif KWH</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                </div>
                <input type="number" step="0.01" name="tarifkwh" id="tarifkwh" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('tarifkwh') }}" required>
            </div>
        </div>
        <div class="mb-4">
            <label for="biayabeban" class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                </div>
                <input type="number" step="0.01" name="biayabeban" id="biayabeban" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('biayabeban') }}" required>
            </div>
        </div>
        <div class="mb-4">
            <label for="berlaku_mulai" class="block text-sm font-medium text-gray-700">Berlaku Mulai</label>
            <input type="date" name="berlaku_mulai" id="berlaku_mulai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('berlaku_mulai') }}" required>
        </div>
        <div class="mb-4">
            <label for="berlaku_sampai" class="block text-sm font-medium text-gray-700">Berlaku Sampai</label>
            <input type="date" name="berlaku_sampai" id="berlaku_sampai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('berlaku_sampai') }}">
            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tarif berlaku tanpa batas waktu.</p>
        </div>

        <div class="mb-4 bg-blue-50 p-4 rounded-md border border-blue-200">
            <p class="text-blue-700 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Nilai <strong>Biaya Beban</strong> dan <strong>Tarif KWH</strong> yang dimasukkan akan otomatis diperbarui pada data Jenis Pelanggan yang dipilih.
            </p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('tarif.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Kembali</a>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchJenisPelanggan');
        const selectElement = document.getElementById('id_jenis');
        const options = Array.from(selectElement.options);

        // Fungsi pencarian
        function filterOptions() {
            const searchText = searchInput.value.toLowerCase();

            options.forEach(option => {
                const searchData = option.getAttribute('data-search-text');

                // Skip option pertama (placeholder)
                if (!searchData) return;

                const isVisible = searchData.includes(searchText);
                option.style.display = isVisible ? '' : 'none';
            });

            // Jika tidak ada hasil yang cocok, tampilkan pesan
            const visibleOptions = options.filter(opt => opt.style.display !== 'none');
            if (visibleOptions.length === 1 && !visibleOptions[0].getAttribute('data-search-text')) {
                // Hanya option placeholder yang terlihat
                selectElement.classList.add('border-red-300');

                // Tambahkan option sementara untuk menampilkan pesan
                const noResultOption = document.createElement('option');
                noResultOption.textContent = 'Tidak ada hasil yang cocok';
                noResultOption.disabled = true;
                noResultOption.id = 'no-result-option';

                // Hapus pesan sebelumnya jika ada
                const existingNoResult = document.getElementById('no-result-option');
                if (existingNoResult) existingNoResult.remove();

                selectElement.appendChild(noResultOption);
                selectElement.value = '';
            } else {
                selectElement.classList.remove('border-red-300');

                // Hapus pesan tidak ada hasil
                const existingNoResult = document.getElementById('no-result-option');
                if (existingNoResult) existingNoResult.remove();
            }
        }

        // Event listener untuk input pencarian
        searchInput.addEventListener('input', filterOptions);

        // Tambahkan event listener untuk select
        selectElement.addEventListener('change', function() {
            // Reset input pencarian saat opsi dipilih
            searchInput.value = '';

            // Tampilkan semua opsi kembali
            options.forEach(option => {
                option.style.display = '';
            });
        });
    });
</script>
@endsection
