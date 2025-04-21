@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Tarif</h1>

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

    <form action="{{ route('tarif.update', $tarifLog->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="id_jenis" class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>
            <select name="id_jenis" id="id_jenis" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" required>
                <option value="">Pilih Jenis Pelanggan</option>
                @foreach($jenisPelanggan as $jenis)
                    <option value="{{ $jenis->id_jenis }}" {{ old('id_jenis', $tarifLog->id_jenis) == $jenis->id_jenis ? 'selected' : '' }}>
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
                <input type="number" step="0.01" name="tarifkwh" id="tarifkwh" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('tarifkwh', $tarifLog->tarifkwh) }}" required>
            </div>
        </div>
        <div class="mb-4">
            <label for="biayabeban" class="block text-sm font-medium text-gray-700">Biaya Beban</label>
            <div class="mt-1 relative rounded-md shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <span class="text-gray-500 sm:text-sm">Rp</span>
                </div>
                <input type="number" step="0.01" name="biayabeban" id="biayabeban" class="pl-10 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('biayabeban', $tarifLog->biayabeban) }}" required>
            </div>
        </div>
        <div class="mb-4">
            <label for="berlaku_mulai" class="block text-sm font-medium text-gray-700">Berlaku Mulai</label>
            <input type="date" name="berlaku_mulai" id="berlaku_mulai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('berlaku_mulai', $tarifLog->berlaku_mulai) }}" required>
        </div>
        <div class="mb-4">
            <label for="berlaku_sampai" class="block text-sm font-medium text-gray-700">Berlaku Sampai</label>
            <input type="date" name="berlaku_sampai" id="berlaku_sampai" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" value="{{ old('berlaku_sampai', $tarifLog->berlaku_sampai) }}">
            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tarif berlaku tanpa batas waktu.</p>
        </div>

        <div class="mb-4 bg-blue-50 p-4 rounded-md border border-blue-200">
            <p class="text-blue-700 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Jika ini adalah tarif terbaru untuk jenis pelanggan ini, nilai <strong>Biaya Beban</strong> dan <strong>Tarif KWH</strong> yang dimasukkan akan otomatis diperbarui pada data Jenis Pelanggan yang dipilih.
            </p>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('tarif.index') }}" class="bg-gray-500 text-white py-2 px-4 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">Kembali</a>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
