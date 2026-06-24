@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Edit Laporan Kerusakan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Update data laporan #{{ $laporan->no_laporan }}</p>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.laporan-kerusakan.index') }}">Kerusakan /</a></li>
            <li class="font-medium text-primary dark:text-white">Edit</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ route('admin.laporan-kerusakan.update', $laporan->id) }}" method="POST" class="p-6 sm:p-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 gap-6">
            <!-- Kendaraan Selection -->
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Pilih Kendaraan <span class="text-error">*</span>
                </label>
                <div class="relative">
                    <select name="id_kend" id="id_kend" required class="w-full appearance-none rounded-lg border border-gray-200 bg-transparent py-2.5 px-4 text-gray-700 outline-none transition focus:border-primary active:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('id_kend') border-error @enderror">
                        @foreach($kendaraans as $kendaraan)
                        <option value="{{ $kendaraan->id_kend }}" {{ old('id_kend', $laporan->id_kend) == $kendaraan->id_kend ? 'selected' : '' }}>
                            {{ $kendaraan->no_polisi }} ({{ $kendaraan->merk }} {{ $kendaraan->tipe }})
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
            </div>

            <!-- Keluhan (Simple) -->
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Informasi Kerusakan (Keluhan Umum) <span class="text-error">*</span>
                </label>
                <textarea name="keluhan" rows="3" required placeholder="Jelaskan masalah secara umum..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('keluhan') border-error @enderror">{{ old('keluhan', $laporan->keluhan) }}</textarea>
                @error('keluhan')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>

            <!-- Detail Teknis -->
            <div class="md:col-span-1">
                <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                    Detail Teknis Kerusakan (Opsional)
                </label>
                <textarea name="detail_teknis" rows="4" placeholder="Jelaskan detail teknis kerusakan jika sudah diketahui..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('detail_teknis') border-error @enderror">{{ old('detail_teknis', $laporan->detail_teknis) }}</textarea>
                @error('detail_teknis')
                    <p class="mt-1 text-xs text-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="mt-8 flex items-center justify-end gap-3 border-t border-gray-100 dark:border-gray-800 pt-6">
            <a href="{{ route('admin.laporan-kerusakan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-6 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-brand-500 px-8 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
