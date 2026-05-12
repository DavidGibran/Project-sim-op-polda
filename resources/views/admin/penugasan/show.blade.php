@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Detail Penugasan
        </h2>
    </div>
    
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('penugasan.index') }}">Penugasan Kendaraan /</a></li>
            <li class="font-medium text-primary dark:text-white">Detail Penugasan</li>
        </ol>
    </nav>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 flex w-full border-l-6 border-success-500 bg-success/10 px-7 py-4 shadow-md dark:bg-success/20 dark:border-success-500 rounded-lg">
    <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-success/20">
        <svg class="text-success-500 dark:text-success-500" width="26" height="26" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6667 4.54546L5.65685 10.5553L2.82843 7.72688" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="w-full">
        <h5 class="mb-1 text-lg font-bold text-success-500 dark:text-success-500">Berhasil</h5>
        <p class="text-base text-gray-600 dark:text-gray-300">{{ session('success') }}</p>
    </div>
</div>
@endif

@if(session('error'))
<div class="mb-6 flex w-full border-l-6 border-error bg-error-50 px-7 py-4 shadow-md dark:bg-error-500/20 dark:border-error rounded-lg">
    <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-error-100 dark:bg-error-500/30">
        <svg class="text-error" width="26" height="26" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.3142 4.68603L4.24316 11.7571M4.24316 4.68603L11.3142 11.7571" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="w-full">
        <h5 class="mb-1 text-lg font-bold text-error">Gagal</h5>
        <p class="text-base text-gray-600 dark:text-gray-300">{{ session('error') }}</p>
    </div>
</div>
@endif

<!-- Header Action -->
<div class="mb-6 flex flex-wrap gap-3">
    <a href="{{ route('penugasan.index') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali ke Daftar Penugasan
    </a>

    <!-- Action Buttons for Details -->
    <a href="{{ route('penugasan.edit', ['penugasan' => $penugasan->id ?? $penugasan->id_tugas ?? 0]) }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:bg-primary/90 transition-all dark:bg-primary dark:text-white">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Edit Penugasan
    </a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3 xl:gap-8">
    
    <!-- Left Column: Main Info (Hero, Waktu, Catatan) -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Hero Header Card -->
        <div class="relative overflow-hidden rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-gray-900/50">
            <!-- Abstract Background -->
            <div class="absolute right-0 top-0 -mt-16 -mr-16 h-64 w-64 rounded-full bg-gradient-to-br from-primary/10 to-primary/0 blur-3xl pointer-events-none"></div>
            
            <div class="relative p-6 sm:p-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div class="flex items-start gap-5">
                    <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-gray-50 border border-gray-100 shadow-inner dark:bg-gray-800/50 dark:border-gray-700">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-black tracking-tight text-gray-900 dark:text-white mb-1.5 leading-tight">{{ $penugasan->tujuan }}</h3>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            {{ $penugasan->pengemudi ?: 'Tanpa Pengemudi' }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-col items-start md:items-end gap-1">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Tanggal Tugas</span>
                    <span class="text-sm sm:text-base font-bold text-gray-900 dark:text-white bg-gray-100 dark:bg-gray-800 px-3 py-1.5 rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">{{ \Carbon\Carbon::parse($penugasan->tgl_tugas)->translatedFormat('l, d M Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Kendaraan Info Card -->
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.02]">
            <h4 class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                Kendaraan yang Digunakan
            </h4>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-100 dark:border-gray-800">
                <div>
                    <span class="block text-2xl font-black text-gray-900 dark:text-white uppercase tracking-tight">{{ $penugasan->kendaraan->no_polisi ?? '-' }}</span>
                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $penugasan->kendaraan->merk ?? '' }} {{ $penugasan->kendaraan->tipe ?? '' }}</span>
                </div>
                <a href="{{ route('kendaraan.show', $penugasan->id_kend ?? 0) }}" class="mt-4 sm:mt-0 inline-flex items-center gap-2 text-sm font-bold text-primary hover:text-primary-600 dark:text-white transition-colors group">
                    Lihat Profil Kendaraan
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>

        <!-- Waktu Pelaksanaan Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.02]">
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Mulai</span>
                <span class="block text-lg font-bold text-gray-900 dark:text-white">{{ $penugasan->waktu_mulai ? \Carbon\Carbon::parse($penugasan->waktu_mulai)->format('d/m/Y H:i') : '-' }}</span>
            </div>
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.02]">
                <span class="block text-[11px] font-bold text-gray-400 uppercase tracking-widest mb-1">Waktu Selesai</span>
                <span class="block text-lg font-bold text-gray-900 dark:text-white">{{ $penugasan->waktu_selesai ? \Carbon\Carbon::parse($penugasan->waktu_selesai)->format('d/m/Y H:i') : '-' }}</span>
            </div>
        </div>

        <!-- Catatan Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.02] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Catatan / Instruksi Khusus</h4>
            </div>
            <div class="p-6">
                @if($penugasan->catatan)
                <div class="relative">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-primary rounded-l-xl"></div>
                    <div class="pl-5 p-4 rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800">
                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap">{{ $penugasan->catatan }}</p>
                    </div>
                </div>
                @else
                <div class="text-center py-4 text-sm font-medium text-gray-400 dark:text-gray-500 italic">
                    Tidak ada catatan atau instruksi khusus.
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar (Status, Odo, Danger Zone) -->
    <div class="lg:col-span-1 space-y-6">
        
        <!-- Status & Odometer Card -->
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.02] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800 text-center">
                <h3 class="font-bold text-gray-900 dark:text-white mb-4">Status Penugasan</h3>
                @php
                    $statusConfig = [
                        'diterbitkan' => 'bg-warning-50 text-warning-700 border-warning-200 dark:bg-warning-500/10 dark:text-warning-400 dark:border-warning-500/20',
                        'diterima'    => 'bg-blue-50 text-blue-700 border-blue-200 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                        'berjalan'    => 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-500/10 dark:text-indigo-400 dark:border-indigo-500/20',
                        'selesai'     => 'bg-success-50 text-success-700 border-success-200 dark:bg-success-500/10 dark:text-success-400 dark:border-success-500/20',
                        'dibatalkan'  => 'bg-error-50 text-error-700 border-error-200 dark:bg-error-500/10 dark:text-error-400 dark:border-error-500/20',
                    ];
                    $configClass = $statusConfig[strtolower($penugasan->status)] ?? 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300';
                @endphp
                <span class="inline-flex items-center justify-center rounded-full border px-5 py-2 text-sm font-bold tracking-wide {{ $configClass }}">
                    @if(in_array(strtolower($penugasan->status), ['berjalan', 'diterbitkan', 'diterima']))
                    <span class="mr-2 h-2 w-2 rounded-full currentColor bg-current animate-pulse"></span>
                    @endif
                    {{ strtoupper($penugasan->status) }}
                </span>
            </div>
            
            <div class="p-6 space-y-5">
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800/30 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KM Awal</span>
                    <span class="font-black text-gray-900 dark:text-white">{{ number_format($penugasan->km_awal, 0, ',', '.') }} KM</span>
                </div>
                
                <div class="flex justify-between items-center bg-gray-50 dark:bg-gray-800/30 p-3 rounded-xl border border-gray-100 dark:border-gray-800">
                    <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">KM Akhir</span>
                    @if($penugasan->status === 'selesai' && $penugasan->km_akhir)
                        <span class="font-black text-gray-900 dark:text-white">{{ number_format($penugasan->km_akhir, 0, ',', '.') }} KM</span>
                    @else
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 italic">Belum tercatat</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Foto Odometer Card -->
        @if($penugasan->status === 'selesai')
        <div class="rounded-3xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.02] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-4 dark:border-gray-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                <h4 class="text-[11px] font-bold text-gray-500 uppercase tracking-widest">Foto Odometer Akhir</h4>
            </div>
            <div class="p-6">
                @if($penugasan->foto_odometer)
                    <div class="relative group rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                        <img src="{{ asset('storage/' . $penugasan->foto_odometer) }}" 
                             alt="Foto Odometer" 
                             class="w-full object-cover max-h-48 transition-transform duration-500 group-hover:scale-105">
                        <a href="{{ asset('storage/' . $penugasan->foto_odometer) }}" 
                           target="_blank"
                           class="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/40 opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm">
                           <svg class="w-8 h-8 text-white mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                           <span class="text-white text-xs font-bold uppercase tracking-wider">Perbesar</span>
                        </a>
                    </div>
                @else
                    <div class="rounded-2xl border-2 border-dashed border-gray-200 dark:border-gray-800 p-8 flex flex-col items-center justify-center text-center">
                        <svg class="w-10 h-10 text-gray-300 dark:text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                        <span class="text-xs font-bold text-gray-400 dark:text-gray-500 uppercase">Tidak ada foto</span>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Danger Zone -->
        @if(!in_array($penugasan->status, ['selesai', 'dibatalkan']))
        <div class="rounded-3xl border-2 border-dashed border-error-200 bg-error-50/50 shadow-sm dark:border-error-500/20 dark:bg-error-500/5 p-6 text-center">
            <h4 class="text-sm font-bold text-error-600 dark:text-error-400 mb-2">Danger Zone</h4>
            <p class="text-xs text-error-500/80 dark:text-error-400/80 mb-4 px-2">Tindakan ini akan menghentikan penugasan secara permanen dan membebaskan kendaraan.</p>
            <button @click="$dispatch('open-cancel-modal', { url: '{{ route('penugasan.batalkan', ['penugasan' => $penugasan->id ?? $penugasan->id_tugas ?? 0]) }}', title: '{{ $penugasan->tujuan }}' })" type="button" class="w-full inline-flex items-center justify-center gap-2 rounded-xl bg-error-500 hover:bg-error-700 px-4 py-3 text-sm font-bold text-white shadow-sm shadow-error/20 hover:bg-error-600/2 hover:shadow-error/40 transition-all">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="15" y1="9" x2="9" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <line x1="9" y1="9" x2="15" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                Batalkan Penugasan
            </button>
        </div>
        @endif
    </div>
</div>

<!-- Modal Batalkan (Copied from index for independent usage) -->
<div x-data="{ open: false, url: '', title: '' }"
    @open-cancel-modal.window="open = true; url = $event.detail.url; title = $event.detail.title"
    x-show="open"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 px-4 py-5"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div @click.outside="open = false"
        class="w-full max-w-[500px] rounded-2xl bg-white p-8 shadow-2xl dark:bg-gray-900"
        x-transition:enter="transition-transform ease-out duration-300"
        x-transition:enter-start="scale-95"
        x-transition:enter-end="scale-100">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Konfirmasi Pembatalan</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Apakah Anda yakin ingin membatalkan penugasan ke <span class="font-bold text-gray-900 dark:text-gray-200" x-text="title"></span>? Tindakan ini tidak dapat diurungkan.</p>

        <div class="flex items-center justify-end gap-3">
            <button @click="open = false" type="button" class="rounded-lg bg-gray-100 px-6 py-2.5 font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                Tutup
            </button>
            <form :action="url" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-error px-6 py-2.5 font-medium text-white hover:bg-opacity-90 transition-all">
                    Batalkan Penugasan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
