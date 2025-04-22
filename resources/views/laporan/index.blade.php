@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Dashboard Pembayaran Listrik</h1>

    <!-- Dashboard Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Pelanggan</h2>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalPelanggan) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Transaksi</h2>
            <p class="text-3xl font-bold text-blue-600">{{ number_format($totalPemakaian) }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Belum Bayar</h2>
            <p class="text-3xl font-bold text-red-600">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-lg font-semibold text-gray-700 mb-2">Total Sudah Bayar</h2>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- History Table with Filters and Export Buttons -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <h2 class="text-xl font-semibold">History Pemakaian</h2>

            <div class="flex flex-col md:flex-row gap-4 w-full md:w-auto">
                <!-- Filters -->
                <div class="flex flex-wrap gap-2">
                    <!-- Filter Tahun -->
                    <select id="yearFilter" class="border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="all">Semua Tahun</option>
                        @php
                            $historyYears = $pemakaians->pluck('tahun')->unique()->sort()->values()->all();
                        @endphp
                        @foreach($historyYears as $year)
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

                <!-- Export Buttons Dropdown -->
                <div class="relative inline-block text-left">
                    <div>
                        <button type="button" id="exportDropdownButton" class="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Export Laporan
                            <svg class="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div id="exportDropdown" class="hidden origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 z-10">
                        <div class="py-1">
                            <button id="exportMonthly" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Bulanan</button>
                            <button id="exportYearly" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Tahunan</button>
                            <button id="exportStatus" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Status</button>
                        </div>
                        <div class="py-1">
                            <button id="exportMonthlyPdf" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF Bulanan</button>
                            <button id="exportYearlyPdf" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF Tahunan</button>
                            <button id="exportStatusPdf" class="text-left w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">PDF Status</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
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

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            {{ $pemakaians->links() }}
        </div>
    </div>

    <!-- Hidden forms for report submission -->
    <form id="monthlyReportForm" action="{{ route('laporan.monthly') }}" method="GET" class="hidden">
        <input type="hidden" name="year" id="monthlyYear">
        <input type="hidden" name="month" id="monthlyMonth">
    </form>

    <form id="monthlyPdfForm" action="{{ route('laporan.monthly.pdf') }}" method="GET" class="hidden">
        <input type="hidden" name="year" id="monthlyPdfYear">
        <input type="hidden" name="month" id="monthlyPdfMonth">
    </form>

    <form id="yearlyReportForm" action="{{ route('laporan.yearly') }}" method="GET" class="hidden">
        <input type="hidden" name="year" id="yearlyYear">
    </form>

    <form id="yearlyPdfForm" action="{{ route('laporan.yearly.pdf') }}" method="GET" class="hidden">
        <input type="hidden" name="year" id="yearlyPdfYear">
    </form>

    <form id="statusReportForm" action="{{ route('laporan.status') }}" method="GET" class="hidden">
        <input type="hidden" name="status" id="statusStatus">
        <input type="hidden" name="year" id="statusYear">
        <input type="hidden" name="month" id="statusMonth">
    </form>

    <form id="statusPdfForm" action="{{ route('laporan.status.pdf') }}" method="GET" class="hidden">
        <input type="hidden" name="status" id="statusPdfStatus">
        <input type="hidden" name="year" id="statusPdfYear">
        <input type="hidden" name="month" id="statusPdfMonth">
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // History filtering functionality
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

        // Export dropdown functionality
        const exportDropdownButton = document.getElementById('exportDropdownButton');
        const exportDropdown = document.getElementById('exportDropdown');

        exportDropdownButton.addEventListener('click', function() {
            exportDropdown.classList.toggle('hidden');
        });

        // Close the dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!exportDropdownButton.contains(event.target) && !exportDropdown.contains(event.target)) {
                exportDropdown.classList.add('hidden');
            }
        });

        // Export report functionality
        document.getElementById('exportMonthly').addEventListener('click', function() {
            const year = yearFilter.value;
            const month = monthFilter.value;

            // Validate required fields
            if (year === 'all' || month === 'all') {
                alert('Silakan pilih tahun dan bulan untuk laporan bulanan');
                return;
            }

            // Set form values and submit
            document.getElementById('monthlyYear').value = year;
            document.getElementById('monthlyMonth').value = month;
            document.getElementById('monthlyReportForm').submit();
        });

        document.getElementById('exportMonthlyPdf').addEventListener('click', function() {
            const year = yearFilter.value;
            const month = monthFilter.value;

            // Validate required fields
            if (year === 'all' || month === 'all') {
                alert('Silakan pilih tahun dan bulan untuk laporan bulanan');
                return;
            }

            // Set form values and submit
            document.getElementById('monthlyPdfYear').value = year;
            document.getElementById('monthlyPdfMonth').value = month;
            document.getElementById('monthlyPdfForm').submit();
        });

        document.getElementById('exportYearly').addEventListener('click', function() {
            const year = yearFilter.value;

            // Validate required fields
            if (year === 'all') {
                alert('Silakan pilih tahun untuk laporan tahunan');
                return;
            }

            // Set form values and submit
            document.getElementById('yearlyYear').value = year;
            document.getElementById('yearlyReportForm').submit();
        });

        document.getElementById('exportYearlyPdf').addEventListener('click', function() {
            const year = yearFilter.value;

            // Validate required fields
            if (year === 'all') {
                alert('Silakan pilih tahun untuk laporan tahunan');
                return;
            }

            // Set form values and submit
            document.getElementById('yearlyPdfYear').value = year;
            document.getElementById('yearlyPdfForm').submit();
        });

        document.getElementById('exportStatus').addEventListener('click', function() {
            const status = statusFilter.value;
            const year = yearFilter.value;
            const month = monthFilter.value;

            // Set form values and submit
            document.getElementById('statusStatus').value = status;
            document.getElementById('statusYear').value = year !== 'all' ? year : '';
            document.getElementById('statusMonth').value = month !== 'all' ? month : '';
            document.getElementById('statusReportForm').submit();
        });

        document.getElementById('exportStatusPdf').addEventListener('click', function() {
            const status = statusFilter.value;
            const year = yearFilter.value;
            const month = monthFilter.value;

            // Set form values and submit
            document.getElementById('statusPdfStatus').value = status;
            document.getElementById('statusPdfYear').value = year !== 'all' ? year : '';
            document.getElementById('statusPdfMonth').value = month !== 'all' ? month : '';
            document.getElementById('statusPdfForm').submit();
        });
    });
</script>
@endsection
