@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-md) md:p-6">
    <!-- Header -->
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Laporkan Kerusakan
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">Silakan jelaskan kendala yang dialami pada kendaraan ini.</p>
        </div>

        <nav>
            <ol class="flex items-center gap-2 text-sm">
                <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('kendaraan.dashboard') }}">Dashboard /</a></li>
                <li class="font-medium text-primary dark:text-white">Laporan Baru</li>
            </ol>
        </nav>
    </div>

    <!-- Alert Messages -->
    @if(session('error'))
    <div class="mb-6 flex w-full border-l-6 border-error bg-error-50 px-7 py-4 shadow-md dark:bg-error-500/20 dark:border-error rounded-lg">
        <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-error-100 dark:bg-error-500/30">
            <svg class="text-error" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="w-full">
            <p class="text-base font-bold text-error">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Form Screen -->

    <!-- Alert Warning (Active Assignment) -->
    @if($penugasanAktif)
    <div class="mb-6 flex w-full border-l-6 border-warning-500 bg-warning/10 px-7 py-4 shadow-md dark:bg-warning/20 dark:border-warning-500 rounded-lg">
        <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-warning/20">
            <svg class="text-warning-500" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="w-full">
            <h5 class="mb-1 text-lg font-bold text-warning-700 dark:text-warning-500">Peringatan Penting</h5>
            <p class="text-sm text-gray-600 dark:text-gray-300">
                Anda memiliki penugasan aktif ke <strong>{{ $penugasanAktif->tujuan }}</strong>. 
                Penugasan ini akan otomatis dibatalkan setelah laporan dikirim.
            </p>
        </div>
    </div>
    @endif

    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 dark:border-gray-800 dark:bg-white/5 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-white">Form Laporan ({{ $mode == 'detail' ? 'Detail' : 'Simple' }})</h3>
        </div>

        <form action="{{ route('kendaraan.laporan-kerusakan.store') }}" method="POST" class="p-6 sm:p-8">
            @csrf
            <input type="hidden" name="mode" value="{{ $mode }}">
            
            <div class="space-y-6">
                <!-- Kendaraan Info (Read Only) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                        Kendaraan
                    </label>
                    <div class="rounded-lg border border-gray-100 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-base font-bold text-gray-900 dark:text-white">{{ $kendaraan->no_polisi }}</p>
                        <p class="text-xs text-gray-500">{{ $kendaraan->merk }} {{ $kendaraan->tipe }}</p>
                    </div>
                </div>

                @if($mode == 'simple')
                <!-- Nomor HP (Only in Simple Mode) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                        Nomor HP / WhatsApp Aktif <span class="text-error">*</span>
                    </label>
                    <input type="text" name="nomor_hp" value="{{ old('nomor_hp') }}" required placeholder="Contoh: 08123456789" class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('nomor_hp') border-error @enderror">
                    <p class="mt-1 text-xs text-gray-500 italic">Nomor ini akan digunakan Admin untuk menghubungi Anda.</p>
                    @error('nomor_hp')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
                @endif

                <!-- Keluhan -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                        Informasi Kerusakan (Keluhan Umum) <span class="text-error">*</span>
                    </label>
                    <textarea name="keluhan" rows="5" required placeholder="Jelaskan kendala yang dialami secara umum..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('keluhan') border-error @enderror">{{ old('keluhan') }}</textarea>
                    @error('keluhan')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>

                @if($mode == 'detail')
                <!-- Detail Teknis (Only in Detail Mode) -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-900 dark:text-white">
                        Detail Teknis Kerusakan (Opsional)
                    </label>
                    <textarea name="detail_teknis" rows="5" placeholder="Jelaskan detail teknis kerusakan (misal: bagian mesin, sensor, dll)..." class="w-full rounded-lg border border-gray-200 bg-transparent py-3 px-4 text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white @error('detail_teknis') border-error @enderror">{{ old('detail_teknis') }}</textarea>
                    @error('detail_teknis')
                        <p class="mt-1 text-xs text-error">{{ $message }}</p>
                    @enderror
                </div>
                @endif
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-end border-t border-gray-100 dark:border-gray-800 pt-6">
                <a href="{{ route('kendaraan.dashboard') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-6 py-3 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-brand-500 px-10 py-3 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
                    Kirim Laporan Kerusakan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
