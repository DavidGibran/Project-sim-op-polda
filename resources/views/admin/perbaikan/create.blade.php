@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Tambah Perbaikan Kendaraan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            @if($laporan)
                Berdasarkan Laporan: {{ $laporan->no_laporan }}
            @else
                Pilih laporan kerusakan untuk diproses
            @endif
        </p>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.laporan-kerusakan.index') }}">Kerusakan /</a></li>
            <li class="font-medium text-primary dark:text-white">Tambah Perbaikan</li>
        </ol>
    </nav>
</div>

<!-- Alert Messages -->
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

<div class="grid grid-cols-1 gap-6 xl:grid-cols-12">
    <!-- Info Laporan (Hanya jika laporan sudah dipilih) -->
    @if($laporan)
    <div class="xl:col-span-4">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <h3 class="font-bold text-gray-900 dark:text-white mb-4 text-lg">Detail Laporan</h3>
            <div class="space-y-4">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase block mb-1">Kendaraan</span>
                    <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $laporan->kendaraan->no_polisi }}</p>
                    <p class="text-xs text-gray-500">{{ $laporan->kendaraan->merk }} {{ $laporan->kendaraan->tipe }}</p>
                </div>
                <hr class="border-gray-100 dark:border-gray-800">
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase block mb-1">Keluhan Umum</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300 italic">"{{ $laporan->keluhan }}"</p>
                </div>
                @if($laporan->detail_teknis)
                <div>
                    <span class="text-xs font-medium text-gray-500 uppercase block mb-1">Detail Teknis</span>
                    <p class="text-sm text-gray-700 dark:text-gray-300">{{ $laporan->detail_teknis }}</p>
                </div>
                @endif
                <hr class="border-gray-100 dark:border-gray-800">
                <div class="flex justify-between">
                    <span class="text-xs font-medium text-gray-500 uppercase">Tgl Lapor</span>
                    <span class="text-xs font-medium text-gray-900 dark:text-white">{{ $laporan->tanggal_lapor->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Perbaikan -->
    <div class="{{ $laporan ? 'xl:col-span-8' : 'xl:col-span-12' }}">
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <form action="{{ route('perbaikan.store') }}" method="POST" class="p-6 sm:p-8">
                @csrf
                
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <!-- Pilih Laporan Kerusakan -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Pilih Laporan Kerusakan <span class="text-error">*</span>
                        </label>
                        <div class="relative">
                            @if($laporan)
                                <input type="hidden" name="id_laporan" value="{{ $laporan->id }}">
                                <div class="w-full rounded-lg border border-gray-100 bg-gray-50 py-2.5 px-4 text-gray-700 dark:border-gray-800 dark:bg-gray-900/50 dark:text-white font-bold">
                                    {{ $laporan->no_laporan }} - {{ $laporan->kendaraan->no_polisi }} ({{ $laporan->kendaraan->merk }} {{ $laporan->kendaraan->tipe }})
                                </div>
                            @else
                                <select name="id_laporan" id="id_laporan" required class="w-full appearance-none rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary active:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('id_laporan') border-error @enderror">
                                    <option value="">-- Pilih Laporan Kerusakan --</option>
                                    @foreach($laporans as $l)
                                    <option value="{{ $l->id }}" {{ old('id_laporan') == $l->id ? 'selected' : '' }}>
                                        {{ $l->no_laporan }} - {{ $l->kendaraan->no_polisi }} ({{ $l->kendaraan->merk }})
                                    </option>
                                    @endforeach
                                </select>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </span>
                            @endif
                        </div>
                        @error('id_laporan')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Teknisi -->
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Nama Teknisi / Bengkel <span class="text-error">*</span>
                        </label>
                        <input type="text" name="teknisi" value="{{ old('teknisi', 'Internal') }}" required placeholder="Contoh: Bpk. Slamet / Bengkel A" class="w-full rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('teknisi') border-error @enderror">
                        @error('teknisi')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai -->
                    <div class="md:col-span-1">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Tanggal Mulai Perbaikan <span class="text-error">*</span>
                        </label>
                        <input type="date" name="tgl_mulai" value="{{ old('tgl_mulai', date('Y-m-d')) }}" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('tgl_mulai') border-error @enderror">
                        @error('tgl_mulai')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan / Detail Perbaikan -->
                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                            Detail Perbaikan / Catatan Pengerjaan <span class="text-error">*</span>
                        </label>
                        <textarea name="detail_perbaikan" rows="4" required placeholder="Jelaskan apa yang akan/sedang diperbaiki..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('detail_perbaikan') border-error @enderror">{{ old('detail_perbaikan') }}</textarea>
                        @error('detail_perbaikan')
                            <p class="mt-1 text-xs text-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
                    <a href="{{ $laporan ? route('admin.laporan-kerusakan.show', $laporan->id) : route('perbaikan.aktif') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                        Batal
                    </a>
                    <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-success-500 px-8 py-2.5 text-sm font-medium text-white hover:bg-success-600 shadow-theme-sm transition-all">
                        Mulai Perbaikan & Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
