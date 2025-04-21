@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">
            Laporan Rentang Tanggal: {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
        </h1>
        <a href="{{ route('laporan.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
            Kembali
        </a>
    </div>

    <!-- Company Information -->
    <div class="mb-6 text-center">
        <h2 class="text-xl font-bold">{{ $companyInfo['name'] }}</h2>
        <p>{{ $companyInfo['address'] }}</p>
        <p>Tel: {{ $companyInfo['phone'] }} | Email: {{ $companyInfo['email'] }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500">Total Transaksi</h3>
            <p class="text-2xl font-bold text-blue-600">{{ number_format(count($pemakaians)) }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500">Total Belum Bayar</h3>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($totalBelumBayar, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white p-4 rounded-lg shadow-md">
            <h3 class="text-sm font-medium text-gray-500">Total Sudah Bayar</h3>
            <p class="text-2xl font-bold text-green-600">Rp {{ number_format($totalSudahBayar, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="bg-white rounded-lg shadow-md overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No Kontrol</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Pelanggan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Pakai</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bayar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pemakaians as $index => $pemakaian)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pemakaian->NoKontrol }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $pemakaian->pelanggan->Nama ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @php
                                $bulanArr = [
                                    '01' => 'Jan', '02' => 'Feb', '03' => 'Mar',
                                    '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
                                    '07' => 'Jul', '08' => 'Agt', '09' => 'Sep',
                                    '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
                                ];
                                $bulanText = isset($bulanArr[$pemakaian->bulan]) ? $bulanArr[$pemakaian->bulan] : $pemakaian->bulan;
                            @endphp
                            {{ $bulanText }} {{ $pemakaian->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($pemakaian->jumlahpakai) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($pemakaian->jumlahbayar, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($pemakaian->status == 'Lunas')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Sudah Bayar
                                </span>
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Belum Bayar
                                </span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-500">Tidak ada data pemakaian untuk rentang tanggal ini</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td colspan="4" class="px-6 py-3 text-sm font-bold text-right">Total:</td>
                    <td class="px-6 py-3 text-sm font-bold">{{ number_format($totalPemakaian) }}</td>
                    <td class="px-6 py-3 text-sm font-bold">Rp {{ number_format($totalJumlahBayar, 0, ',', '.') }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection
