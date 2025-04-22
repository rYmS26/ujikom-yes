@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold">Daftar Pemakaian</h1>
        <a href="{{ route('pemakaian.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Tambah Pemakaian
        </a>
    </div>

    <!-- Filter dan Pencarian -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-4">
        <form action="{{ route('pemakaian.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <!-- Pencarian NoKontrol/Nama -->
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari Pelanggan</label>
                <input
                    type="text"
                    name="search"
                    id="search"
                    placeholder="No Kontrol / Nama Pelanggan"
                    value="{{ request('search') }}"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                >
            </div>

            <!-- Filter Tahun -->
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select name="tahun" id="tahun" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Tahun</option>
                    @for ($i = now()->year; $i >= 2000; $i--)
                        <option value="{{ $i }}" {{ request('tahun') == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            <!-- Filter Bulan -->
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select name="bulan" id="bulan" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Bulan</option>
                    @foreach ([
                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                    ] as $num => $name)
                        <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Filter Status -->
            <div class="flex flex-col">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status" class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    <option value="Lunas" {{ request('status') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                    <option value="Belum Bayar" {{ request('status') == 'Belum Bayar' ? 'selected' : '' }}>Belum Bayar</option>
                    <!-- <option value="Sudah Bayar" {{ request('status') == 'Sudah Bayar' ? 'selected' : '' }}>Sudah Bayar</option> -->
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline-block mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('pemakaian.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Informasi Hasil Filter -->
    <div class="mb-4">
        <p class="text-gray-600">
            Menampilkan {{ $pemakaians->total() }} data pemakaian
            @if(request('search') || request('tahun') || request('bulan') || request('status'))
                dengan filter:
                @if(request('search'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                        Pencarian: {{ request('search') }}
                    </span>
                @endif
                @if(request('tahun'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                        Tahun: {{ request('tahun') }}
                    </span>
                @endif
                @if(request('bulan'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                        Bulan:
                        @foreach ([
                            '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                            '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                            '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                        ] as $num => $name)
                            @if(request('bulan') == $num){{ $name }}@endif
                        @endforeach
                    </span>
                @endif
                @if(request('status'))
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">
                        Status: {{ request('status') }}
                    </span>
                @endif
            @endif
        </p>
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">No Kontrol</th>
                        <th class="py-3 px-6 text-left">Nama Pelanggan</th>
                        <th class="py-3 px-6 text-left">Tahun</th>
                        <th class="py-3 px-6 text-left">Bulan</th>
                        <th class="py-3 px-6 text-left">Meter Awal</th>
                        <th class="py-3 px-6 text-left">Meter Akhir</th>
                        <th class="py-3 px-6 text-left">Jumlah Pakai</th>
                        <th class="py-3 px-6 text-left">Biaya Beban</th>
                        <th class="py-3 px-6 text-left">Biaya Pemakaian</th>
                        <th class="py-3 px-6 text-left">Jumlah Bayar</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($pemakaians as $index => $data)
                    <tr class="border-b border-gray-200 hover:bg-gray-100 {{ $data->status === 'Belum Bayar' ? 'bg-red-50' : '' }}">
                        <td class="py-3 px-6 text-left">{{ $pemakaians->firstItem() + $index }}</td>
                        <td class="py-3 px-6 text-left">{{ $data->NoKontrol }}</td>
                        <td class="py-3 px-6 text-left">{{ $data->pelanggan->nama ?? 'N/A' }}</td>
                        <td class="py-3 px-6 text-left">{{ $data->tahun }}</td>
                        <td class="py-3 px-6 text-left">
                            @foreach ([
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ] as $num => $name)
                                @if($data->bulan == $num){{ $name }}@endif
                            @endforeach
                        </td>
                        <td class="py-3 px-6 text-left">{{ $data->meterawal }}</td>
                        <td class="py-3 px-6 text-left">{{ $data->meterakhir }}</td>
                        <td class="py-3 px-6 text-left">{{ $data->jumlahpakai }}</td>
                        <td class="py-3 px-6 text-left">Rp {{ number_format($data->biayabebanpemakai, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-left">Rp {{ number_format($data->biayapemakaian, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-left">Rp {{ number_format($data->jumlahbayar, 0, ',', '.') }}</td>
                        <td class="py-3 px-6 text-left">
                            <span class="px-2 py-1 rounded-full text-xs {{ $data->status === 'Lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                {{ $data->status }}
                            </span>
                        </td>
                        <td class="py-3 px-6 text-center">
                            <div class="relative inline-block text-left">
                                <!-- <a href="{{ route('pemakaian.edit', $data->id) }}" class="w-4 mr-2 transform hover:text-blue-500 hover:scale-110">
                                    ✏️
                                </a>
                                <form action="{{ route('pemakaian.destroy', $data->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-4 ml-2 transform hover:text-red-500 hover:scale-110" onclick="return confirm('Yakin ingin menghapus data pemakaian ini?')">
                                        🗑️
                                    </button>
                                </form> -->
                                <!-- <a href="{{ route('pembayaran.create', $data->id) }}" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600 ml-2">
                                    Bayar
                                </a> -->
                                <button onclick="toggleDropdown({{ $data->id }})" class="inline-flex items-center justify-center w-8 h-8 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <path fill="currentColor" d="M12 7a2 2 0 1 0-2-2a2 2 0 0 0 2 2m0 10a2 2 0 1 0 2 2a2 2 0 0 0-2-2m0-7a2 2 0 1 0 2 2a2 2 0 0 0-2-2"/>
                                </svg>
                            </button>
                            <div id="dropdown-{{ $data->id }}" class="hidden absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded shadow-md z-50">
                                <a href="{{ route('pemakaian.edit', $data->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Edit
                                </a>
                                <form action="{{ route('pemakaian.destroy', $data->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
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
                        <td colspan="13" class="py-3 px-6 text-center">Tidak ada data pemakaian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <!-- Pagination dengan pengaturan posisi center dan gap -->
    <div class="mt-6 flex justify-center"> <!-- Increased margin-top for spacing -->
    {{ $pemakaians->appends(request()->query())->links() }}
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
