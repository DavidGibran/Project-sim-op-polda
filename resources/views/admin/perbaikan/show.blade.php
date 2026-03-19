@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Detail Perbaikan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('perbaikan.aktif') }}">Perbaikan /</a></li>
            <li class="font-medium text-primary dark:text-white">Detail</li>
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
    <a href="{{ route('perbaikan.aktif') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <!-- Left Column: Repair Status & Actions -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Repair Status Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white">Status Perbaikan</h3>
            </div>
            <div class="p-6">
                <div class="mb-6 text-center">
                    @php
                        $statusConfig = [
                            'dilaporkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400 ring-1 ring-warning-500/30',
                            'diproses'    => 'bg-blue-50 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400 ring-1 ring-blue-500/30',
                            'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400 ring-1 ring-success-500/30',
                        ];
                        $configClass = $statusConfig[$perbaikan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                    @endphp
                    <span class="inline-flex rounded-full px-4 py-1.5 text-sm font-bold {{ $configClass }}">
                        {{ strtoupper($perbaikan->status) }}
                    </span>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-4 italic">
                        Terdaftar sejak {{ $perbaikan->created_at->translatedFormat('d F Y, H:i') }}
                    </p>
                </div>

                <!-- Action Forms -->
                @if($perbaikan->status === 'dilaporkan')
                <div class="border-t border-gray-100 dark:border-gray-800 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Mulai Proses Perbaikan</h4>
                    <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="mulai">
                        <div class="mb-4">
                            <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Teknisi / Bengkel</label>
                            <input type="text" name="teknisi" value="Internal" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-gray-700 outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                        </div>
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-blue-500 px-4 py-2 text-sm font-medium text-white hover:bg-blue-600 transition-all">
                            Konfirmasi Mulai Perbaikan
                        </button>
                    </form>
                </div>
                @elseif($perbaikan->status === 'diproses')
                <div class="border-t border-gray-100 dark:border-gray-800 pt-6">
                    <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Selesaikan Perbaikan</h4>
                    <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="action" value="selesai">
                        <div class="space-y-4">
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Tanggal Selesai</label>
                                <input type="date" name="tgl_selesai" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-gray-700 outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Biaya Akhir (Rp)</label>
                                <input type="number" name="biaya" placeholder="0" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-gray-700 outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                            </div>
                            <div>
                                <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Catatan Penyelesaian</label>
                                <textarea name="catatan_tambahan" rows="3" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-gray-700 outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white" placeholder="Suku cadang yang diganti, dll..."></textarea>
                            </div>
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-success-500 px-4 py-2 text-sm font-medium text-white hover:bg-success-600 transition-all shadow-md">
                                Selesaikan Perbaikan
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        <!-- Vehicle Details Card -->
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] overflow-hidden">
            <div class="border-b border-gray-100 px-6 py-5 dark:border-gray-800">
                <h3 class="font-semibold text-gray-900 dark:text-white">Data Kendaraan</h3>
            </div>
            <div class="p-6 space-y-4 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">No. Polisi</span>
                    <span class="font-bold text-gray-900 dark:text-white">{{ $perbaikan->kendaraan->no_polisi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500 dark:text-gray-400">Merk & Tipe</span>
                    <span class="font-medium text-gray-900 dark:text-gray-200 text-right">{{ $perbaikan->kendaraan->merk }} {{ $perbaikan->kendaraan->tipe }}</span>
                </div>
                <div class="flex justify-between border-t border-gray-50 dark:border-gray-800 pt-4">
                    <span class="text-gray-500 dark:text-gray-400">KM Terakhir</span>
                    <span class="font-medium text-gray-900 dark:text-gray-200">{{ number_format($perbaikan->kendaraan->km_terakhir, 0, ',', '.') }} KM</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Repair Details -->
    <div class="lg:col-span-2 space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03] p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Detail Laporan Keluhan</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8 text-sm">
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase mb-1">Tanggal Laporan</span>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">{{ \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->translatedFormat('l, d F Y') }}</p>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase mb-1">Teknisi / Bengkel</span>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $perbaikan->teknisi ?: '-' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase mb-1">Tanggal Mulai</span>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">{{ $perbaikan->tgl_mulai ? \Carbon\Carbon::parse($perbaikan->tgl_mulai)->translatedFormat('d F Y, H:i') : '-' }}</p>
                </div>
                <div>
                    <span class="block text-xs font-semibold text-gray-400 uppercase mb-1">Tanggal Selesai</span>
                    <p class="text-gray-800 dark:text-gray-200 font-medium font-bold text-success-600">{{ $perbaikan->tgl_selesai ? \Carbon\Carbon::parse($perbaikan->tgl_selesai)->translatedFormat('d F Y') : '-' }}</p>
                </div>
            </div>

            <div class="mb-8">
                <span class="block text-xs font-semibold text-gray-400 uppercase mb-2">Keluhan / Masalah</span>
                <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800 text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
                    {{ $perbaikan->keluhan }}
                </div>
            </div>

            @if($perbaikan->catatan)
            <div class="mb-8">
                <span class="block text-xs font-semibold text-gray-400 uppercase mb-2">Catatan Perbaikan</span>
                <div class="p-4 rounded-xl bg-yellow-50/30 dark:bg-yellow-500/5 border border-yellow-100 dark:border-yellow-900/30 text-gray-700 dark:text-gray-300 whitespace-pre-wrap text-sm leading-relaxed italic">
                    {{ $perbaikan->catatan }}
                </div>
            </div>
            @endif

            @if($perbaikan->status === 'selesai')
            <div class="pt-6 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center">
                <span class="text-gray-500 dark:text-gray-400 font-medium">Total Biaya Perbaikan</span>
                <span class="text-xl font-bold text-gray-900 dark:text-white">Rp {{ number_format($perbaikan->biaya, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
        
        <!-- Danger Zone (Optional - Only for reported/in-progress) -->
        @if($perbaikan->status !== 'selesai')
        <form action="{{ route('perbaikan.destroy', $perbaikan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan laporan perbaikan ini? Status kendaraan akan kembali Tersedia.')">
            @csrf
            @method('DELETE')
            <button type="submit" class="w-full rounded-2xl border-2 border-dashed border-error-300 bg-error-200 p-6 text-left hover:bg-error-500 hover:border-error-500 hover:text-white dark:bg-error-900 dark:text-white dark:border-error-600 dark:hover:bg-error-600 transition-all group">
                <div class="flex flex-col items-center gap-2">
                    <div>
                        <h4 class="text-base font-bold text-error">Batalkan Laporan Perbaikan</h4>
                        <p class="text-xs text-gray-500 hover:text-white dark:hover:text-white dark:text-gray-400 mt-1">Klik untuk membatalkan proses dan mengembalikan status kendaraan menjadi "Tersedia"</p>
                    </div>
                </div>
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
