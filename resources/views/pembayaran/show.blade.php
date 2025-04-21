@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <!-- Customer Information -->
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-gray-800">Detail Pelanggan</h2>

                <!-- PDF Export Button -->
                @auth
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('pembayaran.pdf', $pelanggan->NoKontrol) }}"
                            class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Export PDF
                        </a>
                    @endif
                @endauth

            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-600"><span class="font-semibold">No Kontrol:</span> {{ $pelanggan->NoKontrol }}</p>
                    <p class="text-gray-600"><span class="font-semibold">Nama:</span> {{ $pelanggan->nama }}</p>
                    <p class="text-gray-600"><span class="font-semibold">Alamat:</span> {{ $pelanggan->alamat }}</p>
                </div>
                <div>
                    <p class="text-gray-600"><span class="font-semibold">Jenis Pelanggan:</span>
                        {{ $pelanggan->jenisPlg->nama_jenis ?? 'Tidak tersedia' }}</p>
                    <p class="text-gray-600"><span class="font-semibold">Biaya Beban:</span> Rp
                        {{ number_format($pelanggan->jenisPlg->biayabeban ?? 0, 0, ',', '.') }}</p>
                    <p class="text-red-600 font-bold"><span class="font-semibold">Total Belum Bayar:</span> Rp
                        {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
                </div>
            </div>

            <!-- Transaction Result (will be shown after payment) -->
            <div id="transactionResult"
                class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200 {{ session('lastTagihan') ? '' : 'hidden' }}">
                <h4 class="font-semibold text-green-800 mb-2">Detail Transaksi Terakhir</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                    <p class="text-gray-700"><span class="font-semibold">Jumlah Tagihan:</span> <span id="resultTagihan">
                            Rp {{ session('lastTagihan') ? number_format(session('lastTagihan'), 0, ',', '.') : '0' }}
                        </span></p>
                    <p class="text-gray-700"><span class="font-semibold">Jumlah Dibayar:</span> <span id="resultDibayar">
                            Rp {{ session('lastDibayar') ? number_format(session('lastDibayar'), 0, ',', '.') : '0' }}
                        </span></p>
                    <p class="text-gray-700"><span class="font-semibold">Kembalian:</span> <span id="resultKembalian">
                            Rp {{ session('lastKembalian') ? number_format(session('lastKembalian'), 0, ',', '.') : '0' }}
                        </span></p>
                </div>
            </div>
        </div>

        <!-- Usage History -->
        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="p-6 bg-gray-50 border-b">
                <h3 class="text-xl font-bold text-gray-800">Riwayat Pemakaian</h3>
                <p class="text-sm text-gray-600 mt-1">Centang bulan yang ingin dibayar untuk melakukan pembayaran beberapa
                    bulan sekaligus.</p>
            </div>

            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">
                            <input type="checkbox" id="select-all" class="form-checkbox h-4 w-4 text-blue-600">
                        </th>
                        <th class="py-3 px-6 text-left">Periode</th>
                        <th class="py-3 px-6 text-left">Meter Awal</th>
                        <th class="py-3 px-6 text-left">Meter Akhir</th>
                        <th class="py-3 px-6 text-left">Jumlah Pakai</th>
                        <th class="py-3 px-6 text-left">Biaya Beban</th>
                        <th class="py-3 px-6 text-left">Biaya Pemakaian</th>
                        <th class="py-3 px-6 text-left">Total Bayar</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @forelse($pemakaians as $pemakaian)
                        <tr
                            class="border-b border-gray-200 hover:bg-gray-100 {{ $pemakaian->status === 'Belum Bayar' ? 'bg-red-50' : '' }}">
                            <td class="py-3 px-6 text-left">
                                @if ($pemakaian->status === 'Belum Bayar')
                                    <input type="checkbox" name="pemakaian_checkbox" value="{{ $pemakaian->id }}"
                                        data-amount="{{ $pemakaian->jumlahbayar }}"
                                        class="form-checkbox h-4 w-4 text-blue-600 payment-checkbox">
                                @endif
                            </td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->bulan }}/{{ $pemakaian->tahun }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->meterawal }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->meterakhir }}</td>
                            <td class="py-3 px-6 text-left">{{ $pemakaian->jumlahpakai }}</td>
                            <td class="py-3 px-6 text-left">Rp
                                {{ number_format($pemakaian->biayabebanpemakai, 0, ',', '.') }}</td>
                            <td class="py-3 px-6 text-left">Rp {{ number_format($pemakaian->biayapemakaian, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-6 text-left">Rp {{ number_format($pemakaian->jumlahbayar, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-6 text-left">
                                <span
                                    class="px-2 py-1 rounded-full text-xs {{ $pemakaian->status === 'Lunas' ? 'bg-green-200 text-green-800' : 'bg-red-200 text-red-800' }}">
                                    {{ $pemakaian->status }}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-left">
                                @if ($pemakaian->status === 'Belum Bayar')
                                    <button
                                        onclick="openPaymentModal({{ $pemakaian->id }}, {{ $pemakaian->jumlahbayar }})"
                                        class="bg-blue-500 text-white px-3 py-1 rounded text-xs hover:bg-blue-600">
                                        Bayar
                                    </button>
                                @else
                                    <div class="flex flex-col space-y-1">
                                        <span class="text-green-600 text-xs">Dibayar pada:
                                            {{ $pemakaian->tanggal_bayar ? date('d/m/Y', strtotime($pemakaian->tanggal_bayar)) : '-' }}</span>
                                        <a href="{{ route('pembayaran.receipt.pdf', $pemakaian->id) }}"
                                            class="bg-red-500 text-white px-2 py-1 rounded text-xs hover:bg-red-600 inline-flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                            </svg>
                                            Cetak Struk
                                        </a>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="py-3 px-6 text-center">Tidak ada data pemakaian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Multiple Payment Button -->
            <div class="p-4 flex justify-end">
                <button id="pay-multiple-btn"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:bg-green-300 disabled:cursor-not-allowed hidden"
                    onclick="openMultiplePaymentModal()">
                    Bayar Bulan Terpilih
                </button>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('pembayaran.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                Kembali
            </a>
        </div>
    </div>

    <!-- Single Payment Modal -->
    <div id="paymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Proses Pembayaran</h3>

            <form id="paymentForm" method="POST" action="" onsubmit="return showPaymentResult()">
                @csrf
                <div class="mb-4">
                    <label for="jumlah_bayar" class="block text-sm font-medium text-gray-700">Jumlah Tagihan</label>
                    <input type="number" name="jumlah_bayar" id="jumlah_bayar"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        readonly>
                </div>

                <div class="mb-4">
                    <label for="jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah Dibayar</label>
                    <input type="number" name="jumlah_dibayar" id="jumlah_dibayar"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required min="0" oninput="calculateChange()">
                </div>

                <div class="mb-4">
                    <label for="kembalian" class="block text-sm font-medium text-gray-700">Kembalian</label>
                    <input type="text" id="kembalian"
                        class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closePaymentModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" id="submitPayment"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:bg-green-300 disabled:cursor-not-allowed"
                        disabled>
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Multiple Payment Modal -->
    <div id="multiplePaymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Proses Pembayaran Multiple Bulan</h3>

            <form id="multiplePaymentForm" method="POST"
                action="{{ route('pembayaran.process-multiple', $pelanggan->NoKontrol) }}">
                @csrf
                <div id="selected-months" class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan Terpilih</label>
                    <div id="selected-months-list" class="bg-gray-100 p-2 rounded max-h-32 overflow-y-auto">
                        <!-- Selected months will be listed here -->
                    </div>
                </div>

                <div class="mb-4">
                    <label for="multiple_jumlah_bayar" class="block text-sm font-medium text-gray-700">Total
                        Tagihan</label>
                    <input type="number" name="total_tagihan" id="multiple_jumlah_bayar"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        readonly>
                </div>

                <div class="mb-4">
                    <label for="multiple_jumlah_dibayar" class="block text-sm font-medium text-gray-700">Jumlah
                        Dibayar</label>
                    <input type="number" name="jumlah_dibayar" id="multiple_jumlah_dibayar"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        required min="0" oninput="calculateMultipleChange()">
                </div>

                <div class="mb-4">
                    <label for="multiple_kembalian" class="block text-sm font-medium text-gray-700">Kembalian</label>
                    <input type="text" id="multiple_kembalian"
                        class="mt-1 block w-full bg-gray-100 border-gray-300 rounded-md shadow-sm" readonly>
                </div>

                <div id="selected-pemakaian-ids">
                    <!-- Hidden inputs for selected pemakaian IDs will be added here -->
                </div>

                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeMultiplePaymentModal()"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                        Batal
                    </button>
                    <button type="submit" id="submitMultiplePayment"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 disabled:bg-green-300 disabled:cursor-not-allowed"
                        disabled>
                        Konfirmasi Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentTagihan = 0;
        let currentDibayar = 0;

        // Single payment functionality
        function openPaymentModal(id, amount) {
            document.getElementById('paymentModal').classList.remove('hidden');
            document.getElementById('paymentForm').action = "{{ url('pembayaran/process') }}/" + id;
            document.getElementById('jumlah_bayar').value = amount;
            document.getElementById('jumlah_dibayar').value = '';
            document.getElementById('kembalian').value = '';
            document.getElementById('submitPayment').disabled = true;
            currentTagihan = amount;
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        function calculateChange() {
            const tagihan = parseFloat(document.getElementById('jumlah_bayar').value) || 0;
            const dibayar = parseFloat(document.getElementById('jumlah_dibayar').value) || 0;
            const kembalian = dibayar - tagihan;

            currentDibayar = dibayar;

            if (kembalian >= 0) {
                document.getElementById('kembalian').value = formatRupiah(kembalian);
                document.getElementById('submitPayment').disabled = false;
            } else {
                document.getElementById('kembalian').value = 'Jumlah dibayar kurang';
                document.getElementById('submitPayment').disabled = true;
            }
        }

        function formatRupiah(amount) {
            return 'Rp ' + amount.toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&,');
        }

        function showPaymentResult() {
            // Store payment details for display after form submission
            localStorage.setItem('lastTagihan', currentTagihan);
            localStorage.setItem('lastDibayar', currentDibayar);
            localStorage.setItem('lastKembalian', currentDibayar - currentTagihan);

            // Continue with form submission
            return true;
        }

        // Multiple payment functionality
        let selectedPemakaians = [];
        let totalSelectedAmount = 0;

        // Handle select all checkbox
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="pemakaian_checkbox"]');
            const isChecked = this.checked;

            selectedPemakaians = [];
            totalSelectedAmount = 0;

            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;

                if (isChecked) {
                    const id = parseInt(checkbox.value);
                    const amount = parseFloat(checkbox.dataset.amount);
                    selectedPemakaians.push({
                        id,
                        amount
                    });
                    totalSelectedAmount += amount;
                }
            });

            updatePayMultipleButton();
        });

        // Handle individual checkboxes
        document.querySelectorAll('input[name="pemakaian_checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const id = parseInt(this.value);
                const amount = parseFloat(this.dataset.amount);

                if (this.checked) {
                    selectedPemakaians.push({
                        id,
                        amount
                    });
                    totalSelectedAmount += amount;
                } else {
                    const index = selectedPemakaians.findIndex(item => item.id === id);
                    if (index !== -1) {
                        totalSelectedAmount -= selectedPemakaians[index].amount;
                        selectedPemakaians.splice(index, 1);
                    }

                    // Uncheck "select all" if any individual checkbox is unchecked
                    document.getElementById('select-all').checked = false;
                }

                updatePayMultipleButton();
            });
        });

        function updatePayMultipleButton() {
            const payButton = document.getElementById('pay-multiple-btn');

            if (selectedPemakaians.length > 0) {
                payButton.classList.remove('hidden');
                payButton.textContent =
                    `Bayar ${selectedPemakaians.length} Bulan Terpilih (Rp ${formatNumber(totalSelectedAmount)})`;
            } else {
                payButton.classList.add('hidden');
            }
        }

        function openMultiplePaymentModal() {
            if (selectedPemakaians.length === 0) return;

            document.getElementById('multiplePaymentModal').classList.remove('hidden');
            document.getElementById('multiple_jumlah_bayar').value = totalSelectedAmount;
            document.getElementById('multiple_jumlah_dibayar').value = '';
            document.getElementById('multiple_kembalian').value = '';
            document.getElementById('submitMultiplePayment').disabled = true;

            // Clear previous selected IDs
            document.getElementById('selected-pemakaian-ids').innerHTML = '';

            // Add hidden inputs for selected pemakaian IDs
            selectedPemakaians.forEach(item => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'pemakaian_ids[]';
                input.value = item.id;
                document.getElementById('selected-pemakaian-ids').appendChild(input);
            });

            // Display selected months
            const selectedMonthsList = document.getElementById('selected-months-list');
            selectedMonthsList.innerHTML = '';

            const pemakaianRows = document.querySelectorAll('tbody tr');
            selectedPemakaians.forEach(item => {
                pemakaianRows.forEach(row => {
                    const checkbox = row.querySelector('input[type="checkbox"]');
                    if (checkbox && parseInt(checkbox.value) === item.id) {
                        const period = row.querySelectorAll('td')[1].textContent.trim();
                        const amount = row.querySelectorAll('td')[7].textContent.trim();

                        const monthItem = document.createElement('div');
                        monthItem.className =
                            'flex justify-between py-1 border-b border-gray-200 last:border-0';
                        monthItem.innerHTML = `
                        <span>${period}</span>
                        <span>${amount}</span>
                    `;
                        selectedMonthsList.appendChild(monthItem);
                    }
                });
            });
        }

        function closeMultiplePaymentModal() {
            document.getElementById('multiplePaymentModal').classList.add('hidden');
        }

        function calculateMultipleChange() {
            const tagihan = parseFloat(document.getElementById('multiple_jumlah_bayar').value) || 0;
            const dibayar = parseFloat(document.getElementById('multiple_jumlah_dibayar').value) || 0;
            const kembalian = dibayar - tagihan;

            if (kembalian >= 0) {
                document.getElementById('multiple_kembalian').value = formatRupiah(kembalian);
                document.getElementById('submitMultiplePayment').disabled = false;
            } else {
                document.getElementById('multiple_kembalian').value = 'Jumlah dibayar kurang';
                document.getElementById('submitMultiplePayment').disabled = true;
            }
        }

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Check if we have payment details to display (after page reload from form submission)
        document.addEventListener('DOMContentLoaded', function() {
            const lastTagihan = localStorage.getItem('lastTagihan');
            const lastDibayar = localStorage.getItem('lastDibayar');
            const lastKembalian = localStorage.getItem('lastKembalian');

            if (lastTagihan && lastDibayar && lastKembalian) {
                // Display the transaction result
                document.getElementById('resultTagihan').textContent = formatRupiah(parseFloat(lastTagihan));
                document.getElementById('resultDibayar').textContent = formatRupiah(parseFloat(lastDibayar));
                document.getElementById('resultKembalian').textContent = formatRupiah(parseFloat(lastKembalian));
                document.getElementById('transactionResult').classList.remove('hidden');

                // Clear the stored values after displaying them
                localStorage.removeItem('lastTagihan');
                localStorage.removeItem('lastDibayar');
                localStorage.removeItem('lastKembalian');
            }
        });
    </script>
@endsection
