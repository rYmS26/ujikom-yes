@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Edit Pelanggan</h1>

    <!-- Success Notification -->
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Error Notification -->
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('customers.update', $pelanggan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="nama" name="nama" value="{{ old('nama', $pelanggan->nama) }}" maxlength="100" required>
        </div>
        <div class="mb-4">
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="alamat" name="alamat" maxlength="255" rows="3" required>{{ old('alamat', $pelanggan->alamat) }}</textarea>
        </div>
        <div class="mb-4">
            <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="telepon" name="telepon" value="{{ old('telepon', $pelanggan->telepon) }}" maxlength="15" required>
        </div>
        <div class="mb-4">
            <label for="jenis_plg" class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>
            <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="jenis_plg" name="jenis_plg" required>
                <option value="" disabled>Pilih Jenis Pelanggan</option>
                @foreach($jenisPelanggan as $jenis)
                    <option value="{{ $jenis->id_jenis }}" {{ $jenis->id_jenis == old('jenis_plg', $pelanggan->jenis_plg) ? 'selected' : '' }}>
                        {{ $jenis->id_jenis }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Update</button>
    </form>
</div>
@endsection
