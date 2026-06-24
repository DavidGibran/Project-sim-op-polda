@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Detail Laporan Kerusakan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">No: {{ $laporan->no_laporan }}</p>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.laporan-kerusakan.index') }}">Laporan Kerusakan /</a></li>
            <li class="font-medium text-primary dark:text-white">Detail</li>
        </ol>
    </nav>
</div>

<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
    <!-- Main Info -->
    <div class="xl:col-span-8">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-white/5">
                <h3 class="font-bold text-gray-900 dark:text-white text-lg">Informasi Kerusakan</h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Status & Mode -->
                <div class="flex flex-wrap gap-4 items-center">
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Status</p>
                        @php
                            $statusConfig = [
                                'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400 ring-1 ring-warning-500/30',
                                'diproses'    => 'bg-blue-50 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400 ring-1 ring-blue-500/30',
                                'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400 ring-1 ring-success-500/30',
                            ];
                            $configClass = $statusConfig[$laporan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $configClass }}">
                            {{ ucfirst($laporan->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Mode Input</p>
                        <span class="inline-flex rounded-full px-3 py-1 text-sm font-semibold {{ $laporan->mode == 'detail' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 ring-1 ring-blue-500/30' : 'bg-orange-50 text-orange-700 dark:bg-orange-500/20 dark:text-orange-400 ring-1 ring-orange-500/30' }}">
                            {{ ucfirst($laporan->mode) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-medium uppercase tracking-wider text-gray-500 mb-1">Sumber</p>
                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst($laporan->sumber) }}</span>
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-gray-800">

                <!-- Keluhan Umum -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Keluhan Umum</h4>
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $laporan->keluhan }}</p>
                    </div>
                </div>

                <!-- Detail Teknis -->
                <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Detail Teknis</h4>
                    @if($laporan->detail_teknis)
                    <div class="rounded-xl bg-blue-50/30 p-4 dark:bg-blue-500/5 border border-blue-100 dark:border-blue-900/30">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $laporan->detail_teknis }}</p>
                    </div>
                    @else
                    <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/50 border border-dashed border-gray-300 dark:border-gray-700">
                        <p class="text-gray-500 italic text-sm text-center">Belum ada detail teknis yang dicatat.</p>
                    </div>
                    @endif
                </div>

                @if($laporan->perbaikan)
                <hr class="border-gray-100 dark:border-gray-800">
                <!-- Relasi Perbaikan -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-success-50 dark:bg-success-500/10 border border-success-100 dark:border-success-500/20">
                    <div>
                        <h4 class="text-success-700 dark:text-success-400 font-bold text-sm uppercase">Telah Diproses Perbaikan</h4>
                        <p class="text-xs text-success-600 dark:text-success-500">Status Perbaikan: <strong>{{ ucfirst($laporan->perbaikan->status) }}</strong></p>
                    </div>
                    <a href="{{ route('perbaikan.show', $laporan->perbaikan->id) }}" class="inline-flex items-center gap-2 text-sm font-bold text-success-700 hover:underline dark:text-success-400">
                        Lihat Detail Perbaikan
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                </div>
                @endif
            </div>

            <div class="bg-gray-50 px-6 py-4 dark:bg-white/5 border-t border-gray-100 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <a href="{{ route('admin.laporan-kerusakan.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        Kembali ke Daftar
                    </a>
                    <div class="flex gap-3">
                        @if($laporan->status === 'diterbitkan')
                        <a href="{{ route('admin.laporan-kerusakan.edit', $laporan->id) }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                            Edit Laporan
                        </a>
                        <a href="{{ route('perbaikan.create', ['laporan_id' => $laporan->id]) }}" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-6 py-2 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
                            Buat Perbaikan
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div class="xl:col-span-4 space-y-6">
        <!-- Kendaraan -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Data Kendaraan</h3>
            <div class="space-y-3">
                {{-- Plat Nomor --}}
                <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">Plat Nomor</p>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $laporan->kendaraan->no_polisi }}</p>
                </div>
                {{-- Unit (merk & tipe bisa panjang) --}}
                <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">Unit</p>
                    <p class="text-sm font-semibold text-gray-900 dark:text-white leading-snug">{{ $laporan->kendaraan->merk }} {{ $laporan->kendaraan->tipe }}</p>
                </div>
                {{-- Kategori --}}
                <div class="rounded-xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 px-4 py-3">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500 mb-1">Kategori</p>
                    <p class="text-sm text-gray-900 dark:text-white">{{ $laporan->kendaraan->kategori_kendaraan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Pelapor (Hanya jika ada nomor HP) -->
        @if($laporan->nomor_hp || $laporan->id_penugasan)
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Informasi Pelapor</h3>
            <div class="space-y-4">
                @if($laporan->nomor_hp)
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase">Kontak (WA)</span>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $laporan->nomor_hp) }}" target="_blank" class="text-sm font-bold text-brand-500 hover:underline">
                        {{ $laporan->nomor_hp }}
                    </a>
                </div>
                @endif
                
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase">Tgl Lapor</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $laporan->tanggal_lapor->format('d/m/Y H:i') }}</span>
                </div>

                @if($laporan->id_penugasan)
                <hr class="border-gray-100 dark:border-gray-800">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase mb-2 block">Penugasan Saat Melapor</span>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $laporan->penugasan->pengemudi }}</p>
                    <p class="text-xs text-gray-500">Tujuan: {{ $laporan->penugasan->tujuan }}</p>
                    @if($laporan->sumber == 'kendaraan')
                    <p class="text-xs text-error mt-1 font-medium italic dark:text-gray-500">* Penugasan ini otomatis dibatalkan</p>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @else
        <!-- Jika tidak ada info pelapor, tampilkan info dasar saja -->
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4">Metadata Laporan</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase">Tgl Lapor</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ $laporan->tanggal_lapor->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between items-start">
                    <span class="text-xs font-medium text-gray-500 uppercase">Dibuat Oleh</span>
                    <span class="text-sm text-gray-900 dark:text-white">{{ ucfirst($laporan->sumber) }}</span>
                </div>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
