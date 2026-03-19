@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Tambah Laporan Perbaikan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('perbaikan.aktif') }}">Perbaikan /</a></li>
            <li class="font-medium text-primary dark:text-white">Tambah</li>
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

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ route('perbaikan.store') }}" method="POST" class="p-6 sm:p-8">
        @csrf
        
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Kendaraan Selection -->
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Pilih Kendaraan <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <select name="id_kend" id="id_kend" required class="w-full appearance-none rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary active:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('id_kend') border-error @enderror">
                        <option value="">-- Pilih Kendaraan (Hanya yang Tersedia) --</option>
                        @foreach($kendaraans as $kendaraan)
                        <option value="{{ $kendaraan->id_kend }}" {{ old('id_kend') == $kendaraan->id_kend ? 'selected' : '' }}>
                            {{ $kendaraan->no_polisi }} ({{ $kendaraan->merk }} {{ $kendaraan->tipe }}) - Status: {{ $kendaraan->status }}
                        </option>
                        @endforeach
                    </select>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none">
                        <svg class="h-4 w-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </span>
                </div>
                @error('id_kend')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
                <p class="mt-2 text-xs text-gray-500 italic">Hanya kendaraan yang tidak sedang dalam perbaikan yang muncul di sini.</p>
            </div>

            <!-- Tanggal Lapor -->
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Tanggal Laporan <span class="text-error">*</span>
                </label>
                <input type="date" name="tanggal_lapor" value="{{ old('tanggal_lapor', date('Y-m-d')) }}" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('tanggal_lapor') border-error @enderror">
                @error('tanggal_lapor')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keluhan -->
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Detail Keluhan / Masalah <span class="text-error">*</span>
                </label>
                <textarea name="keluhan" rows="4" required placeholder="Jelaskan masalah pada kendaraan (contoh: Rem blong, Ganti Oli, dll...)" class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('keluhan') border-error @enderror">{{ old('keluhan') }}</textarea>
                @error('keluhan')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Catatan Awal -->
            <div class="md:col-span-2">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Catatan Tambahan (Opsional)
                </label>
                <textarea name="catatan" rows="2" placeholder="Informasi tambahan jika ada..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">{{ old('catatan') }}</textarea>
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
            <a href="{{ route('perbaikan.aktif') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-8 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
                Simpan & Update Status Kendaraan
            </button>
        </div>
    </form>
</div>
@endsection
