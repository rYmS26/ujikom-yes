@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Tarif</h1>
        <a href="{{ route('tarif.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Tambah Tarif
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-visible relative z-0">
        <table class="min-w-full table-auto">
            <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">No</th>
                    <th class="py-3 px-6 text-left">ID Jenis</th>
                    <th class="py-3 px-6 text-left">Nama Jenis</th>
                    <th class="py-3 px-6 text-left">Tarif KWH</th>
                    <th class="py-3 px-6 text-left">Biaya Beban</th>
                    <th class="py-3 px-6 text-left">Berlaku Mulai</th>
                    <th class="py-3 px-6 text-left">Berlaku Sampai</th>
                    <th class="py-3 px-6 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 text-sm font-light">
                @forelse($tarifLogs as $index => $tarif)
                <tr class="border-b border-gray-200 hover:bg-gray-100">
                    <td class="py-3 px-6 text-left">{{ $tarifLogs->firstItem() + $index }}</td>
                    <td class="py-3 px-6 text-left">{{ $tarif->id_jenis }}</td>
                    <td class="py-3 px-6 text-left">{{ $tarif->jenisPlg->nama_jenis ?? 'N/A' }}</td>
                    <td class="py-3 px-6 text-left">Rp {{ number_format($tarif->tarifkwh, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-left">Rp {{ number_format($tarif->biayabeban, 0, ',', '.') }}</td>
                    <td class="py-3 px-6 text-left">{{ date('d/m/Y', strtotime($tarif->berlaku_mulai)) }}</td>
                    <td class="py-3 px-6 text-left">
                        {{ $tarif->berlaku_sampai ? date('d/m/Y', strtotime($tarif->berlaku_sampai)) : 'Tidak terbatas' }}
                    </td>
                    <td class="py-3 px-6 text-center">
                        <div class="relative inline-block text-left z-55">
                            <button onclick="toggleDropdown({{ $tarif->id }})" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M12 7a2 2 0 1 0-2-2a2 2 0 0 0 2 2m0 10a2 2 0 1 0 2 2a2 2 0 0 0-2-2m0-7a2 2 0 1 0 2 2a2 2 0 0 0-2-2"/>
                                </svg>
                            </button>
                            <div id="dropdown-{{ $tarif->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md z-50">
                                <a href="{{ route('tarif.edit', $tarif->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Edit
                                </a>
                                <form action="{{ route('tarif.destroy', $tarif->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus tarif ini?')">
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
                @empty
                <tr>
                    <td colspan="8" class="py-3 px-6 text-center">Tidak ada data tarif.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination dengan pengaturan posisi center dan gap -->
    <div class="mt-6 flex justify-center gap-6"> <!-- Added gap to separate pagination controls -->
        {{ $tarifLogs->links() }}
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
