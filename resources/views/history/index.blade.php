@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">History Pemakaian</h1>
            <div class="flex space-x-2">
                <!-- Filter Tahun -->
                <select id="yearFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua Tahun</option>
                    @php
                        $years = $pemakaians->pluck('tahun')->unique()->sort()->values()->all();
                    @endphp
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>

                <!-- Filter Bulan -->
                <select id="monthFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua Bulan</option>
                    <option value="1">Januari</option>
                    <option value="2">Februari</option>
                    <option value="3">Maret</option>
                    <option value="4">April</option>
                    <option value="5">Mei</option>
                    <option value="6">Juni</option>
                    <option value="7">Juli</option>
                    <option value="8">Agustus</option>
                    <option value="9">September</option>
                    <option value="10">Oktober</option>
                    <option value="11">November</option>
                    <option value="12">Desember</option>
                </select>

                <!-- Filter Status -->
                <select id="statusFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="all">Semua Status</option>
                    <option value="Lunas">Lunas</option>
                    <option value="Belum Bayar">Belum Bayar</option>
                </select>
            </div>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">No</th>
                        <th class="py-3 px-6 text-left">No Kontrol</th>
                        <th class="py-3 px-6 text-left">Nama</th>
                        <th class="py-3 px-6 text-left">Tahun</th>
                        <th class="py-3 px-6 text-left">Bulan</th>
                        <th class="py-3 px-6 text-left">Jumlah Pakai</th>
                        <th class="py-3 px-6 text-left">Jenis Pelanggan</th>
                        <th class="py-3 px-6 text-left">Jumlah Bayar</th>
                        <th class="py-3 px-6 text-left">Status</th>
                    </tr>
                </thead>
                <tbody id="historyTable" class="text-gray-600 text-sm font-light">
                    @foreach ($pemakaians as $index => $pemakaian)
                        <tr class="border-b border-gray-200 hover:bg-gray-100 data-row"
                            data-status="{{ $pemakaian->status }}"
                            data-year="{{ $pemakaian->tahun }}"
                            data-month="{{ $pemakaian->bulan }}">
                            <td class="py-3 px-6 text-left">{{ $index + 1 }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->NoKontrol }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->pelanggan->nama }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->tahun }}</td>
                            <td class="py-3 px-6 text-left">
                                @php
                                    $bulanNames = [
                                        '1' => 'Januari',
                                        '2' => 'Februari',
                                        '3' => 'Maret',
                                        '4' => 'April',
                                        '5' => 'Mei',
                                        '6' => 'Juni',
                                        '7' => 'Juli',
                                        '8' => 'Agustus',
                                        '9' => 'September',
                                        '10' => 'Oktober',
                                        '11' => 'November',
                                        '12' => 'Desember'
                                    ];
                                @endphp
                                {{ $bulanNames[$pemakaian->bulan] ?? $pemakaian->bulan }}
                            </td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->jumlahpakai }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->pelanggan->jenis_plg }}</td>
                            <td class="py-3 px-6 text-left">Rp {{ number_format($pemakaian->jumlahbayar, 2) }}</td>
                            <td class="py-3 px-6 text-left">
                                <span
                                    class="px-2 py-1 rounded-full text-xs {{ $pemakaian->status === 'Lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $pemakaian->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination dengan pengaturan posisi center dan gap -->
        <div class="mt-6 flex justify-center"> <!-- Increased margin-top for spacing -->
            {{ $pemakaians->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusFilter = document.getElementById('statusFilter');
            const yearFilter = document.getElementById('yearFilter');
            const monthFilter = document.getElementById('monthFilter');
            const rows = document.querySelectorAll('.data-row');

            // Initial check for any URL parameters
            applyFilters();

            // Add event listeners to all filters
            statusFilter.addEventListener('change', applyFilters);
            yearFilter.addEventListener('change', applyFilters);
            monthFilter.addEventListener('change', applyFilters);

            function applyFilters() {
                const statusValue = statusFilter.value;
                const yearValue = yearFilter.value;
                const monthValue = monthFilter.value;

                let visibleCount = 0;

                rows.forEach((row, index) => {
                    const rowStatus = row.getAttribute('data-status');
                    const rowYear = row.getAttribute('data-year');
                    const rowMonth = row.getAttribute('data-month');

                    const statusMatch = statusValue === 'all' || rowStatus === statusValue;
                    const yearMatch = yearValue === 'all' || rowYear === yearValue;
                    const monthMatch = monthValue === 'all' || rowMonth === monthValue;

                    if (statusMatch && yearMatch && monthMatch) {
                        row.style.display = ''; // Show row
                        visibleCount++;
                        // Update the row number
                        row.cells[0].textContent = visibleCount;
                    } else {
                        row.style.display = 'none'; // Hide row
                    }
                });

                // Show message if no results
                const tableBody = document.getElementById('historyTable');
                if (visibleCount === 0) {
                    // Check if we already have a message row
                    let messageRow = document.getElementById('no-data-message');
                    if (!messageRow) {
                        messageRow = document.createElement('tr');
                        messageRow.id = 'no-data-message';
                        const messageCell = document.createElement('td');
                        messageCell.colSpan = 9; // Span all columns
                        messageCell.className = 'py-4 px-6 text-center text-gray-500';
                        messageCell.textContent = 'Tidak ada data yang sesuai dengan filter yang dipilih';
                        messageRow.appendChild(messageCell);
                        tableBody.appendChild(messageRow);
                    } else {
                        messageRow.style.display = '';
                    }
                } else {
                    // Hide the message row if it exists
                    const messageRow = document.getElementById('no-data-message');
                    if (messageRow) {
                        messageRow.style.display = 'none';
                    }
                }
            }
        });
    </script>
@endsection
