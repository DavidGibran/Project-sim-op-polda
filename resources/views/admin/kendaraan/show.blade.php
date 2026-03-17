@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Detail Kendaraan
        </h2>
    </div>
    
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('kendaraan.index') }}">Master Kendaraan /</a></li>
            <li class="font-medium text-primary dark:text-white">Detail Kendaraan</li>
        </ol>
    </nav>
</div>

<!-- Header Action -->
<div class="mb-6 flex">
    <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Master Kendaraan
    </a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Card 1: Informasi Kendaraan -->
    <div class="lg:col-span-1">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white">Informasi Kendaraan</h3>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">No. Polisi</span>
                    <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $kendaraan->no_polisi }}</span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Merk & Tipe</span>
                        <span class="font-semibold text-gray-900 dark:text-white">{{ $kendaraan->merk }} {{ $kendaraan->tipe }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Tahun</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $kendaraan->tahun }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">KM Terakhir</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ number_format($kendaraan->km_terakhir, 0, ',', '.') }} KM</span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Kategori</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $kendaraan->kategori_kendaraan }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Jenis</span>
                        <span class="font-medium text-gray-900 dark:text-white">{{ $kendaraan->jenis_kendaraan }}</span>
                    </div>
                </div>
                
                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1">Status</span>
                    @php
                        $statusConfig = [
                            'Tersedia' => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
                            'Dipakai' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                            'Perbaikan' => 'bg-error-50 text-error-700 dark:bg-error-500/20 dark:text-error-400',
                            'Diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400',
                        ];
                        $configClass = $statusConfig[$kendaraan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $configClass }}">
                        {{ $kendaraan->status }}
                    </span>
                </div>

                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Keterangan Penggunaan</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-gray-300 mt-1">
                        {{ $kendaraan->keterangan_penggunaan ?: '-' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Side: Riwayat Tables -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Card 2: Riwayat Penugasan Terbaru -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Riwayat Penugasan Terbaru</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">5 Data Terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Tujuan</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Pengemudi</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($kendaraan->penugasans as $tugas)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-3">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ \Carbon\Carbon::parse($tugas->tgl_tugas)->translatedFormat('d F Y') }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($tugas->tujuan, 30) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $tugas->pengemudi ?: 'Tidak mengisi nama' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $s_color = match($tugas->status) {
                                        'berjalan' => 'bg-blue-50 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400',
                                        'selesai' => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
                                        'dibatalkan' => 'bg-error-50 text-error-700 dark:bg-error-500/20 dark:text-error-400',
                                        'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $s_color }}">
                                    {{ ucfirst($tugas->status) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada riwayat penugasan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Card 3: Riwayat Perbaikan Terbaru -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex justify-between items-center">
                <h3 class="font-semibold text-gray-900 dark:text-white">Riwayat Perbaikan Terbaru</h3>
                <span class="text-xs text-gray-500 dark:text-gray-400">5 Data Terakhir</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-white/5">
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Tanggal Lapor</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Keluhan</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Bengkel</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Biaya</th>
                            <th class="px-5 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                        @forelse($kendaraan->perbaikans as $perbaikan)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02]">
                            <td class="px-5 py-3">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-200">{{ \Carbon\Carbon::parse($perbaikan->tanggal_laporan)->translatedFormat('d F Y') }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ Str::limit($perbaikan->keluhan, 30) }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $perbaikan->nama_bengkel ?: '-' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-sm text-gray-600 dark:text-gray-300">{{ $perbaikan->estimasi_biaya ? 'Rp ' . number_format($perbaikan->estimasi_biaya, 0, ',', '.') : '-' }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $s_color = match($perbaikan->status) {
                                        'Dilaporkan' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/20 dark:text-yellow-400',
                                        'Proses Perbaikan' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                                        'Selesai' => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
                                        default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
                                    };
                                @endphp
                                <span class="inline-flex rounded-full px-2 py-0.5 text-xs font-medium {{ $s_color }}">
                                    {{ $perbaikan->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada riwayat perbaikan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
