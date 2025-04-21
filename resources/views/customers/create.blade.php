@extends('layouts.app')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Tambah Pelanggan</h1>
    <form action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="nama" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="nama" name="nama" maxlength="100" required>
        </div>
        <div class="mb-4">
            <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
            <textarea class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="alamat" name="alamat" maxlength="255" rows="3" required></textarea>
        </div>
        <div class="mb-4">
            <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
            <input type="number" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="telepon" name="telepon" maxlength="15" required>
        </div>
        <div class="mb-4">
            <label for="jenis_plg" class="block text-sm font-medium text-gray-700">Jenis Pelanggan</label>
            <input type="text" id="searchJenis" placeholder="Cari jenis pelanggan..." class="mt-3 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <select class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" id="jenis_plg" name="jenis_plg" required>
                <option value="" disabled selected>Pilih Jenis Pelanggan</option>
                @foreach($jenisPelanggan as $jenis)
                    <option value="{{ $jenis->id_jenis }}">{{ $jenis->id_jenis }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan</button>
    </form>
</div>

{{-- Script pencarian manual --}}
<script>
    document.getElementById('searchJenis').addEventListener('keyup', function () {
        let input = this.value.toLowerCase();
        let select = document.getElementById('jenis_plg');
        let options = select.options;

        for (let i = 0; i < options.length; i++) {
            let text = options[i].text.toLowerCase();
            if (text.includes(input) || options[i].value.toLowerCase().includes(input)) {
                options[i].style.display = '';
            } else {
                options[i].style.display = 'none';
            }
        }
    });
</script>
@endsection
