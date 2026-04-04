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

    @if(!in_array($penugasan->status, ['selesai', 'dibatalkan']))
    <button @click="$dispatch('open-cancel-modal', { url: '{{ route('penugasan.batalkan', ['penugasan' => $penugasan->id ?? $penugasan->id_tugas ?? 0]) }}', title: '{{ $penugasan->tujuan }}' })" type="button" class="inline-flex items-center justify-center gap-2 rounded-lg bg-error px-4 py-2 text-sm font-medium text-white hover:bg-opacity-90 transition-all">
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
            <circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <line x1="15" y1="9" x2="9" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <line x1="9" y1="9" x2="15" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
        Batalkan Penugasan
    </button>
    @endif
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Card 1: Informasi Penugasan -->
    <div class="lg:col-span-2">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white">Detail Penugasan Tugas</h3>
            </div>
            <div class="p-6 space-y-6">
                <!-- Status Row -->
                <div class="flex flex-col sm:flex-row gap-4 sm:justify-between sm:items-center">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Status Penugasan</span>
                        @php
                            $statusConfig = [
                                'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400 ring-1 ring-warning-500/30',
                                'berjalan'    => 'bg-blue/20 text-primary-700 dark:bg-blue/20 dark:text-blue-400 ring-1 ring-primary/30',
                                'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400 ring-1 ring-success-500/30',
                                'dibatalkan'  => 'bg-error-50 text-error-700 dark:bg-error-500/20 dark:text-error-400 ring-1 ring-error-500/30',
                            ];
                            $configClass = $statusConfig[$penugasan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex rounded-full px-4 py-1.5 text-sm font-bold {{ $configClass }}">
                            {{ ucfirst($penugasan->status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Kendaraan</span>
                        <div class="mt-1">
                            <span class="font-bold text-gray-900 dark:text-white text-base">{{ $penugasan->kendaraan->no_polisi ?? '-' }}</span>
                            <span class="block text-sm text-gray-600 dark:text-gray-300 mt-0.5">{{ $penugasan->kendaraan->merk ?? '' }} {{ $penugasan->kendaraan->tipe ?? '' }}</span>
                        </div>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Tujuan</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $penugasan->tujuan }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Pengemudi</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $penugasan->pengemudi ?: 'Tidak mengisi nama' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Tanggal Tugas</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ \Carbon\Carbon::parse($penugasan->tgl_tugas)->translatedFormat('l, d F Y') }}</span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">KM Awal</span>
                        <span class="block text-lg font-bold text-gray-900 dark:text-white mt-1">{{ number_format($penugasan->km_awal, 0, ',', '.') }} KM</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">KM Akhir</span>
                        @if($penugasan->status === 'selesai')
                            <span class="block text-lg font-bold text-gray-900 dark:text-white mt-1">{{ number_format($penugasan->km_akhir, 0, ',', '.') }} KM</span>
                        @else
                            <span class="block text-sm font-medium text-gray-400 dark:text-gray-500 mt-1 italic">Belum tercatat</span>
                        @endif
                    </div>
                </div>

                @if($penugasan->status === 'selesai')
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pb-6 border-b border-gray-100 dark:border-gray-800">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Waktu Mulai</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $penugasan->waktu_mulai ? $penugasan->waktu_mulai->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Waktu Selesai</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $penugasan->waktu_selesai ? $penugasan->waktu_selesai->format('d/m/Y H:i') : '-' }}</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-3">Foto Odometer Akhir</span>
                    @if($penugasan->foto_odometer)
                        <div class="relative group max-w-sm">
                            <img src="{{ asset('storage/' . $penugasan->foto_odometer) }}" 
                                 alt="Foto Odometer" 
                                 class="rounded-2xl border border-gray-200 shadow-sm dark:border-gray-800 w-full object-cover max-h-64">
                            <a href="{{ asset('storage/' . $penugasan->foto_odometer) }}" 
                               target="_blank"
                               class="absolute inset-0 flex items-center justify-center bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity rounded-2xl text-white font-medium">
                               Lihat Ukuran Penuh
                            </a>
                        </div>
                    @else
                        <div class="rounded-xl border-2 border-dashed border-gray-200 p-8 text-center text-gray-400 dark:border-gray-800">
                            Tidak ada foto odometer yang diunggah.
                        </div>
                    @endif
                </div>
                @endif

                <div class="pt-2">
                    <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Catatan/Instruksi</span>
                    <p class="text-sm font-medium text-gray-800 dark:text-gray-300 mt-2 bg-gray-50 p-4 rounded-xl dark:bg-white/5 border border-transparent dark:border-gray-800 w-full overflow-hidden">
                        {{ $penugasan->catatan ?: 'Tidak ada catatan khusus.' }}
                    </p>
                </div>
            </div>
        </div>
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
