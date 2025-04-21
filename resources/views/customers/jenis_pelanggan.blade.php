@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Jenis Pelanggan</h1>
        <a href="{{ route('customers.createJenisPlg') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Tambah Jenis Pelanggan
        </a>
    </div>

    <!-- Tambahkan relative dan overflow-visible agar dropdown bisa tampil -->
    <div class="bg-white shadow-md rounded-lg overflow-visible relative z-0">
        <table class="min-w-full table-auto relative">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">ID Jenis</th>
                    <th class="py-3 px-6 text-left">Nama Jenis</th>
                    <th class="py-3 px-6 text-left">Biaya Beban</th>
                    <th class="py-3 px-6 text-left">Tarif KWH</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @foreach ($jenisPelanggan as $jenis)
                <tr class="border-b border-gray-200 hover:bg-gray-100 relative">
                    <td class="py-3 px-6 text-left">{{ $jenis->id_jenis }}</td>
                    <td class="py-3 px-6 text-left">{{ $jenis->nama_jenis }}</td>
                    <td class="py-3 px-6 text-left">{{ number_format($jenis->biayabeban, 2) }}</td>
                    <td class="py-3 px-6 text-left">{{ number_format($jenis->tarifkwh, 2) }}</td>
                    <td class="py-3 px-6 text-center">
                        <div class="relative inline-block text-left z-55">
                            <button onclick="toggleDropdown({{ $jenis->id }})" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M12 7a2 2 0 1 0-2-2a2 2 0 0 0 2 2m0 10a2 2 0 1 0 2 2a2 2 0 0 0-2-2m0-7a2 2 0 1 0 2 2a2 2 0 0 0-2-2"/>
                                </svg>
                            </button>
                            <div id="dropdown-{{ $jenis->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md z-50">
                                <a href="{{ route('customers.editJenisPlg', $jenis->id_jenis) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Edit
                                </a>
                                <form action="{{ route('customers.delete_jenis_pelanggan', $jenis->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination dengan pengaturan posisi center dan gap -->
    <div class="mt-6 flex justify-center"> <!-- Increased margin-top for spacing -->
        {{ $jenisPelanggan->links() }}
    </div>
</div>

<script>
    function toggleDropdown(id) {
        // Tutup semua dropdown
        document.querySelectorAll("[id^='dropdown-']").forEach(drop => {
            if (drop.id !== `dropdown-${id}`) {
                drop.classList.add('hidden');
            }
        });

        const dropdown = document.getElementById(`dropdown-${id}`);
        dropdown.classList.toggle('hidden');
    }

    // Tutup dropdown jika klik di luar
    window.addEventListener('click', function(e) {
        const dropdowns = document.querySelectorAll("[id^='dropdown-']");
        dropdowns.forEach(dropdown => {
            if (!dropdown.parentNode.contains(e.target)) {
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
@endsection
