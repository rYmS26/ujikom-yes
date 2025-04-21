<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Status Pembayaran - Cari Pelanggan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css') {{-- Tailwind CSS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchForm = document.getElementById('searchForm');
            const searchResults = document.getElementById('searchResults');
            const filterSection = document.getElementById('filterSection');
            const query = "{{ $query ?? '' }}";

            // If there's a query parameter, show results immediately
            if (query) {
                filterSection.style.display = 'block';
                searchResults.style.display = 'block';
                document.getElementById('query').value = query;
            } else {
                // Hide results and filter initially if no query
                searchResults.style.display = 'none';
                filterSection.style.display = 'none';
            }

            searchForm.addEventListener('submit', function(event) {
                // Allow the form to submit normally to reload the page with the query parameter
            });

            // Filter functionality
            const filterButtons = document.querySelectorAll('.filter-btn');
            filterButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const filter = this.dataset.filter;

                    // Remove active class from all buttons
                    filterButtons.forEach(btn => btn.classList.remove('bg-[#FF2D20]', 'text-white'));
                    filterButtons.forEach(btn => btn.classList.add('bg-gray-200', 'text-gray-700'));

                    // Add active class to clicked button
                    this.classList.remove('bg-gray-200', 'text-gray-700');
                    this.classList.add('bg-[#FF2D20]', 'text-white');

                    // Filter the payment cards
                    const paymentCards = document.querySelectorAll('.payment-card');
                    paymentCards.forEach(card => {
                        if (filter === 'all') {
                            card.style.display = 'block';
                        } else if (filter === 'paid' && card.classList.contains('paid')) {
                            card.style.display = 'block';
                        } else if (filter === 'unpaid' && card.classList.contains('unpaid')) {
                            card.style.display = 'block';
                        } else {
                            card.style.display = 'none';
                        }
                    });
                });
            });

            // Month filter
            const monthSelect = document.getElementById('monthFilter');
            monthSelect.addEventListener('change', function() {
                const selectedMonth = this.value;
                const paymentCards = document.querySelectorAll('.payment-card');

                paymentCards.forEach(card => {
                    if (selectedMonth === 'all' || card.dataset.month === selectedMonth) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });

            // Year filter
            const yearSelect = document.getElementById('yearFilter');
            yearSelect.addEventListener('change', function() {
                const selectedYear = this.value;
                const paymentCards = document.querySelectorAll('.payment-card');

                paymentCards.forEach(card => {
                    if (selectedYear === 'all' || card.dataset.year === selectedYear) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    </script>
</head>
<body class="bg-gray-100 dark:bg-zinc-900">
    <main class="mt-10 pb-10">
        <div class="flex justify-center items-start min-h-screen px-4">
            <div class="w-full max-w-4xl">
                <x-application-logo class="block h-9 w-full mx-auto fill-current text-gray-800 dark:text-gray-200 mb-10" />
                <!-- Search Form -->
                <div class="bg-white p-6 rounded-lg shadow-md dark:bg-zinc-800 mb-6">
                    <h2 class="text-xl font-semibold text-black dark:text-white mb-4">Cek Status Pembayaran</h2>
                    <form id="searchForm" action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <label for="query" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">NoKontrol</label>
                            <input type="text" id="query" name="query" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring-[#FF2D20] dark:bg-zinc-700 dark:border-zinc-600 dark:text-white" placeholder="Masukkan nomor kontrol" required>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="w-full md:w-auto bg-[#FF2D20] text-white py-2 px-6 rounded-md hover:bg-[#e0261c] focus:outline-none focus:ring-2 focus:ring-[#FF2D20] focus:ring-offset-2 dark:focus:ring-offset-zinc-800">
                                Cari
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Filter Section - Initially Hidden -->
                <div id="filterSection" class="bg-white p-6 rounded-lg shadow-md dark:bg-zinc-800 mb-6" style="display: none;">
                    <h3 class="text-lg font-semibold text-black dark:text-white mb-4">Filter Status Pembayaran</h3>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status Pembayaran</label>
                            <div class="flex space-x-2">
                                <button type="button" class="filter-btn bg-[#FF2D20] text-white px-3 py-1 rounded-md text-sm" data-filter="all">
                                    Semua
                                </button>
                                <button type="button" class="filter-btn bg-gray-200 text-gray-700 px-3 py-1 rounded-md text-sm" data-filter="paid">
                                    Lunas
                                </button>
                                <button type="button" class="filter-btn bg-gray-200 text-gray-700 px-3 py-1 rounded-md text-sm" data-filter="unpaid">
                                    Belum Bayar
                                </button>
                            </div>
                        </div>

                        <!-- Month Filter -->
                        <div>
                            <label for="monthFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                            <select id="monthFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring-[#FF2D20] dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
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
                        </div>

                        <!-- Year Filter -->
                        <div>
                            <label for="yearFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                            <select id="yearFilter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-[#FF2D20] focus:ring-[#FF2D20] dark:bg-zinc-700 dark:border-zinc-600 dark:text-white">
                                <option value="all">Semua Tahun</option>
                                @for ($year = date('Y'); $year >= date('Y') - 5; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <!-- Customer Info Summary -->
                    <div class="bg-gray-50 dark:bg-zinc-700 p-4 rounded-md">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">No Kontrol:</span>
                                    <span id="customerNoKontrol">{{ $pemakaian->isNotEmpty() ? $pemakaian->first()->NoKontrol : ($pelanggan ? $pelanggan->NoKontrol : '-') }}</span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Nama:</span>
                                    <span id="customerName">{{ $pemakaian->isNotEmpty() && $pemakaian->first()->pelanggan ? $pemakaian->first()->pelanggan->nama : ($pelanggan ? $pelanggan->nama : '-') }}</span>
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Total Belum Bayar:</span>
                                    <span id="totalUnpaid" class="text-red-600 font-semibold">
                                        Rp {{ number_format($pemakaian->where('status', 'Belum Bayar')->sum('jumlahbayar'), 0, ',', '.') }}
                                    </span>
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    <span class="font-medium">Total Sudah Bayar:</span>
                                    <span id="totalPaid" class="text-green-600 font-semibold">
                                        Rp {{ number_format($pemakaian->where('status', 'Lunas')->sum('jumlahbayar'), 0, ',', '.') }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search Results - Initially Hidden -->
                <div id="searchResults" style="display: none;">
                    @if($pemakaian->isNotEmpty())
                        <h3 class="text-lg font-semibold mb-4 text-black dark:text-white">Data Pemakaian</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($pemakaian as $item)
                                @php
                                    $isPaid = $item->status === 'Lunas';
                                    $statusClass = $isPaid ? 'paid' : 'unpaid';
                                    $statusBgClass = $isPaid ? 'bg-green-50 dark:bg-green-900/20' : 'bg-red-50 dark:bg-red-900/20';
                                    $statusBorderClass = $isPaid ? 'border-green-200 dark:border-green-800' : 'border-red-200 dark:border-red-800';

                                    // Get month name
                                    $monthNames = [
                                        '1' => 'Januari', '2' => 'Februari', '3' => 'Maret', '4' => 'April',
                                        '5' => 'Mei', '6' => 'Juni', '7' => 'Juli', '8' => 'Agustus',
                                        '9' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    ];
                                    $monthName = $monthNames[$item->bulan] ?? $item->bulan;
                                @endphp

                                <div class="payment-card {{ $statusClass }} {{ $statusBgClass }} border {{ $statusBorderClass }} rounded-lg shadow-sm p-4" data-month="{{ $item->bulan }}" data-year="{{ $item->tahun }}">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-semibold text-gray-800 dark:text-white">{{ $monthName }} - {{ $item->tahun }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">No. Kontrol: {{ $item->NoKontrol }}</p>
                                        </div>
                                        <div>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $isPaid ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                                {{ $item->status }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                                        <div>
                                            <p class="text-gray-600 dark:text-gray-400">Meter Awal</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->meterawal }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 dark:text-gray-400">Meter Akhir</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->meterakhir }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 dark:text-gray-400">Jumlah Pakai</p>
                                            <p class="font-medium text-gray-800 dark:text-white">{{ $item->jumlahpakai }} m³</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-600 dark:text-gray-400">Biaya Beban</p>
                                            <p class="font-medium text-gray-800 dark:text-white">Rp {{ number_format($item->biayabebanpemakai, 0, ',', '.') }}</p>
                                        </div>
                                    </div>

                                    <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                                        <div class="flex justify-between items-center">
                                            <p class="font-semibold text-gray-800 dark:text-white">Total Bayar:</p>
                                            <p class="font-bold text-lg {{ $isPaid ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                                Rp {{ number_format($item->jumlahbayar ?? 0, 0, ',', '.') }}
                                            </p>
                                        </div>

                                        @if($isPaid && $item->tanggal_bayar)
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                Dibayar pada: {{ date('d/m/Y', strtotime($item->tanggal_bayar)) }}
                                            </p>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                    @if(!$isPaid)
                                        <div class="inline-flex items-center justify-center w-full bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 py-1.5 px-4 rounded-md text-sm border border-red-200 dark:border-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            Belum Dibayar
                                        </div>
                                    @else
                                        <div class="flex justify-between gap-2">
                                            <div class="inline-flex items-center justify-center flex-grow bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 py-1.5 px-4 rounded-md text-sm border border-green-200 dark:border-green-800">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                Sudah Dibayar
                                            </div>
                                            {{-- <a href="{{ route('pembayaran.receipt.pdf', $item->id) }}" class="inline-flex items-center justify-center bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white py-1.5 px-3 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 text-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            </a> --}}
                                        </div>
                                    @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif(isset($query) && !empty($query))
                        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-zinc-800 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Tidak ada data pemakaian untuk NoKontrol: <strong>{{ $query }}</strong></p>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">Silakan periksa kembali nomor kontrol Anda.</p>
                        </div>
                    @else
                        <div class="bg-white p-6 rounded-lg shadow-md dark:bg-zinc-800 text-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-400 dark:text-gray-500 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Silakan masukkan nomor kontrol untuk melihat status pembayaran.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>
</html>
