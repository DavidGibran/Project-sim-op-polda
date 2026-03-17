@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Edit Penugasan Kendaraan
        </h2>
    </div>
    
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('penugasan.index') }}">Penugasan /</a></li>
            <li class="font-medium text-primary dark:text-white">Edit</li>
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

<!-- Form Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ route('penugasan.update', ['penugasan' => $penugasan->id ?? $penugasan->id_tugas ?? 0]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="p-6">
            
            <div class="mb-6 p-4 rounded-xl border border-gray-200 bg-gray-50 dark:bg-white/5 dark:border-gray-800">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Kendaraan Operasional</span>
                        <p class="font-bold text-gray-800 dark:text-white">{{ $penugasan->kendaraan->no_polisi ?? '-' }} - {{ $penugasan->kendaraan->merk ?? '' }} {{ $penugasan->kendaraan->tipe ?? '' }}</p>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">KM Awal Penugasan</span>
                        <p class="font-bold text-gray-800 dark:text-white">{{ number_format($penugasan->km_awal, 0, ',', '.') }} KM</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-3 pt-3 border-t border-gray-200 dark:border-gray-800">Data asensial di atas tidak dapat diedit karena dikunci oleh sistem saat penugasan dibuat.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Tujuan -->
                <div class="sm:col-span-2">
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Tujuan Penugasan <span class="text-error">*</span></label>
                    <input type="text" name="tujuan" value="{{ old('tujuan', $penugasan->tujuan) }}" required placeholder="Misal: Patroli Wilayah Barat" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('tujuan') border-error @enderror">
                    @error('tujuan') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Pengemudi -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Nama Pengemudi <span class="text-gray-400 text-xs">(Opsional)</span></label>
                    <input type="text" name="pengemudi" value="{{ old('pengemudi', $penugasan->pengemudi) }}" placeholder="Misal: Bripka Sudarsono" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('pengemudi') border-error @enderror">
                    @error('pengemudi') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Tanggal Tugas -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Tanggal Tugas <span class="text-error">*</span></label>
                    <input type="date" name="tgl_tugas" value="{{ old('tgl_tugas', $penugasan->tgl_tugas) }}" required class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('tgl_tugas') border-error @enderror">
                    @error('tgl_tugas') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Catatan -->
            <div class="mt-6">
                <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Catatan <span class="text-gray-400 text-xs">(Opsional)</span></label>
                <textarea name="catatan" rows="4" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('catatan') border-error @enderror" placeholder="Deskripsi atau instruksi tambahan">{{ old('catatan', $penugasan->catatan) }}</textarea>
                @error('catatan') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
            </div>
            
        </div>
        
        <div class="border-t border-gray-100 dark:border-gray-800 px-6 py-5 flex items-center justify-end gap-3 bg-gray-50 dark:bg-white/5 rounded-b-2xl">
            <a href="{{ route('penugasan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-center text-sm font-medium text-success-600 border border-success-400 hover:bg-success-400 hover:text-white dark:bg-primary dark:hover:bg-success-800 dark:hover:border-success-800 dark:border-success-800 transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
