@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Jenis Pelanggan</h1>

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

    <form action="{{ route('customers.store_jenis_pelanggan') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="id_jenis" class="block text-sm font-medium text-gray-700">ID Jenis</label>
            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="id_jenis" name="id_jenis" maxlength="50" value="{{ old('id_jenis') }}" required>
        </div>
        <div class="mb-4">
            <label for="nama_jenis" class="block text-sm font-medium text-gray-700">Nama Jenis</label>
            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="nama_jenis" name="nama_jenis" maxlength="100" value="{{ old('nama_jenis') }}" required>
        </div>

        <div class="mb-4 bg-yellow-50 p-4 rounded-md border border-yellow-200">
            <p class="text-yellow-700 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                </svg>
                Nilai <strong>Biaya Beban</strong> dan <strong>Tarif KWH</strong> akan diisi melalui form Tarif setelah Jenis Pelanggan dibuat.
            </p>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan</button>
    </form>
</div>
@endsection
