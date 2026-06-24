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

<div class="space-y-6">
    <!-- Hero Banner (Header) -->
    <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
        <!-- Abstract Background -->
        <div class="absolute right-0 top-0 -mt-16 -mr-16 h-64 w-64 rounded-full bg-gradient-to-br from-primary/10 to-primary/0 blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 -mb-16 -ml-16 h-64 w-64 rounded-full bg-gradient-to-tr from-blue-500/10 to-blue-500/0 blur-3xl pointer-events-none"></div>
        
        <div class="relative p-8 sm:p-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="flex items-center gap-6">
                <div class="flex h-20 w-20 shrink-0 items-center justify-center rounded-2xl bg-gray-50 border border-gray-100 shadow-inner dark:bg-gray-800/50 dark:border-gray-700">
                    <svg class="h-10 w-10 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight text-gray-900 dark:text-white uppercase mb-1">{{ $kendaraan->no_polisi }}</h3>
                    <p class="text-base font-medium text-gray-500 dark:text-gray-400">
                        Dipegang oleh: <span class="text-gray-800 dark:text-gray-200">{{ $kendaraan->nama_pemegang ?: 'Belum ditentukan' }}</span>
                    </p>
                </div>
            </div>
            <div class="flex flex-col items-start md:items-end gap-2">
                @php
                    $statusConfig = [
                        'Tersedia'    => 'bg-success-50 text-success-700 border-success-200 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20',
                        'Diterbitkan' => 'bg-warning-50 text-warning-700 border-warning-200 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20',
                        'Diterima'    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                        'Dipakai'     => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                        'Perjalanan'  => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                        'Perbaikan'   => 'bg-error-50 text-error-700 border-error-200 dark:bg-error-500/10 dark:text-error-400 dark:border-error-500/20',
                    ];
                    $configClass = $statusConfig[ucfirst(strtolower($kendaraan->status))] ?? 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
                @endphp
                <span class="inline-flex items-center justify-center rounded-full border px-4 py-1.5 text-sm font-bold tracking-wide {{ $configClass }}">
                    <span class="mr-2 h-2 w-2 rounded-full currentColor bg-current animate-pulse"></span>
                    {{ strtoupper($kendaraan->status) }}
                </span>
                <span class="text-xs font-medium text-gray-400 dark:text-gray-500">Terdaftar sejak {{ $kendaraan->created_at->format('Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-4 gap-4">
        <!-- Nama Pada SIMAK -->
        <div class="col-span-2 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Pada SIMAK</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white">{{ $kendaraan->nama_pada_simak }}</span>
        </div>
        <!-- BBM -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis BBM</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white truncate">{{ $kendaraan->bbm }}</span>
        </div>
        <!-- KM Terakhir -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">KM Terakhir</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white truncate">{{ number_format($kendaraan->km_terakhir, 0, ',', '.') }}</span>
        </div>
        <!-- Merk -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Merk</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white truncate">{{ $kendaraan->merk }}</span>
        </div>
        <!-- Jenis -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white truncate">{{ $kendaraan->jenis_kendaraan }}</span>
        </div>
        <!-- Tahun -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tahun</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white">{{ $kendaraan->tahun }}</span>
        </div>
        <!-- Kategori -->
        <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] hover:shadow-md transition-shadow">
            <span class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kategori</span>
            <span class="block text-base font-bold text-gray-900 dark:text-white truncate">{{ $kendaraan->kategori_kendaraan }}</span>
        </div>
    </div>

    <!-- Tipe Kendaraan (Full Width) -->
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.02] flex gap-4 items-start">
        <svg class="w-6 h-6 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
        <div>
            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">Tipe Kendaraan</h4>
            <p class="text-base font-bold text-gray-900 dark:text-white leading-relaxed">{{ $kendaraan->tipe }}</p>
        </div>
    </div>

    <!-- Usage Note (If exists) -->
    @if($kendaraan->keterangan_penggunaan)
    <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800/80 dark:bg-white/[0.02] flex gap-4 items-start">
        <svg class="w-6 h-6 text-gray-400 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <div>
            <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest mb-1.5">Keterangan Penggunaan Khusus</h4>
            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-relaxed">{{ $kendaraan->keterangan_penggunaan }}</p>
        </div>
    </div>
    @endif

    <!-- Split History Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 pt-2">
        <!-- Riwayat Penugasan -->
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.02] flex flex-col overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-white/[0.01]">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-blue-50 text-blue-500 dark:bg-blue-500/10 dark:text-blue-400 ring-1 ring-blue-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Penugasan</h3>
                </div>
                <span class="px-3 py-1 rounded-full bg-white border border-gray-200 shadow-sm text-xs font-bold text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">3 Terbaru</span>
            </div>
            <div class="p-6 flex-1">
                @if($kendaraan->penugasans->count() > 0)
                <div class="relative border-l-2 border-gray-100 dark:border-gray-800 ml-3 space-y-8 py-2">
                    @foreach($kendaraan->penugasans as $tugas)
                    <div class="relative pl-6 group">
                        <!-- Timeline Dot -->
                        <div class="absolute -left-[9px] top-1.5 h-4 w-4 rounded-full border-2 border-white bg-blue-500 dark:border-gray-900 dark:bg-blue-500 ring-4 ring-blue-50 group-hover:ring-blue-100 dark:ring-blue-500/10 dark:group-hover:ring-blue-500/20 transition-all"></div>
                        
                        <!-- Content -->
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <span class="text-[11px] font-black text-blue-500 dark:text-blue-400 tracking-wider">{{ \Carbon\Carbon::parse($tugas->tgl_tugas)->translatedFormat('d M Y') }}</span>
                            @php
                                $ts_color = match(strtolower($tugas->status)) {
                                    'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400',
                                    'diterima'    => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                    'berjalan'    => 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400',
                                    'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                    'dibatalkan'  => 'bg-error-50 text-error-700 dark:bg-error-500/10 dark:text-error-400',
                                    default       => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
                                };
                            @endphp
                            <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $ts_color }}">
                                {{ $tugas->status }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-2">{{ $tugas->tujuan }}</h4>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ $tugas->pengemudi ?: 'Tanpa Pengemudi' }}
                        </p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="h-full flex flex-col items-center justify-center text-center py-10 opacity-60">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Belum ada riwayat penugasan</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Riwayat Perbaikan -->
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.02] flex flex-col overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 flex justify-between items-center bg-gray-50/50 dark:bg-white/[0.01]">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 rounded-xl bg-orange-50 text-orange-500 dark:bg-orange-500/10 dark:text-orange-400 ring-1 ring-orange-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Riwayat Perbaikan</h3>
                </div>
                <span class="px-3 py-1 rounded-full bg-white border border-gray-200 shadow-sm text-xs font-bold text-gray-500 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400">3 Terbaru</span>
            </div>
            <div class="p-6 flex-1">
                @if($kendaraan->perbaikans->count() > 0)
                <div class="relative border-l-2 border-gray-100 dark:border-gray-800 ml-3 space-y-8 py-2">
                    @foreach($kendaraan->perbaikans as $perbaikan)
                    <div class="relative pl-6 group">
                        <!-- Timeline Dot -->
                        <div class="absolute -left-[9px] top-1.5 h-4 w-4 rounded-full border-2 border-white bg-orange-500 dark:border-gray-900 dark:bg-orange-500 ring-4 ring-orange-50 group-hover:ring-orange-100 dark:ring-orange-500/10 dark:group-hover:ring-orange-500/20 transition-all"></div>
                        
                        <!-- Content -->
                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                            <span class="text-[11px] font-black text-orange-500 dark:text-orange-400 tracking-wider">{{ \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->translatedFormat('d M Y') }}</span>
                            @php
                                $ps_color = match($perbaikan->status) {
                                    'dilaporkan' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400',
                                    'diproses' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/10 dark:text-blue-400',
                                    'selesai' => 'bg-success-50 text-success-700 dark:bg-success-500/10 dark:text-success-400',
                                    default => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300'
                                };
                            @endphp
                            <span class="inline-flex rounded-md px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider {{ $ps_color }}">
                                {{ $perbaikan->status }}
                            </span>
                        </div>
                        <h4 class="text-sm font-bold text-gray-900 dark:text-white leading-tight mb-2 line-clamp-1" title="{{ \Illuminate\Support\Str::before($perbaikan->catatan, "\n--- Penyelesaian") }}">
                            {{ \Illuminate\Support\Str::before($perbaikan->catatan, "\n--- Penyelesaian") ?: '-' }}
                        </h4>
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                {{ $perbaikan->teknisi ?: 'Internal' }}
                            </span>
                            @if($perbaikan->biaya)
                            <span class="text-xs font-bold text-gray-700 dark:text-gray-200 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded-md border border-gray-200 dark:border-gray-700">
                                Rp {{ number_format($perbaikan->biaya, 0, ',', '.') }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="h-full flex flex-col items-center justify-center text-center py-10 opacity-60">
                    <div class="w-16 h-16 bg-gray-50 dark:bg-gray-800/50 rounded-full flex items-center justify-center mb-3">
                        <svg class="w-8 h-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-500 dark:text-gray-400">Belum ada riwayat perbaikan</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
