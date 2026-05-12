@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

    {{-- 
        Breadcrumb / Header
        Struktur dibuat mirip area admin agar konsisten secara UI
    --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Dashboard Pengemudi
            </h2>
        </div>

        <nav>
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a
                        class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400"
                        href="{{ route('kendaraan.dashboard') }}"
                    >
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-primary dark:text-white">
                    Ringkasan Kendaraan
                </li>
            </ol>
        </nav>
    </div>

    {{-- 
        Alert message
        Menampilkan notifikasi sukses / error dari controller
    --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- 
        GRID CARD
        Dashboard terdiri dari 2 card utama
    --}}
    <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

        {{-- ===================================================== --}}
        {{-- CARD 1 : INFORMASI KENDARAAN --}}
        {{-- ===================================================== --}}
        <div class="xl:col-span-5 flex flex-col">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] h-full flex flex-col">
                
                {{-- Header card --}}
                <div class="mb-5 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Kendaraan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Data kendaraan yang sedang login.
                        </p>
                    </div>

                    {{-- 
                        Tombol laporkan kendala atau aksi sesuai status perjalanan
                    --}}
                    @if(($dashboardData['status_perjalanan'] ?? '') === 'berjalan')
                        {{-- Sedang berjalan: arahkan ke halaman perjalanan aktif --}}
                        <a
                            href="{{ route('kendaraan.perjalanan-aktif') }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap flex-shrink-0 rounded-lg bg-success-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-success-700 dark:bg-success-600 dark:hover:bg-success-500 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="5 3 19 12 5 21 5 3"></polygon></svg>
                            Perjalanan Aktif
                        </a>
                    @else
                        {{-- Masih di garasi (diterbitkan / diterima / tidak ada penugasan): bisa laporkan kerusakan --}}
                        <a
                            href="{{ route('kendaraan.laporan-kerusakan.create') }}"
                            class="inline-flex items-center justify-center gap-2 whitespace-nowrap flex-shrink-0 rounded-lg bg-warning-500 px-5 py-2 text-sm font-medium text-white hover:opacity-90"
                        >
                            Laporkan Kerusakan
                        </a>
                    @endif
                </div>

                {{-- Konten utama card --}}
                <div class="space-y-4">
                    {{-- No Polisi --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Plat Nomor
                        </p>
                        <p class="mt-1 text-lg font-bold text-gray-900 dark:text-white">
                            {{ $dashboardData['no_polisi'] ?? '-' }}
                        </p>
                    </div>

                    {{-- Tipe kendaraan --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Tipe Kendaraan
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ trim(($dashboardData['merk'] ?? '-') . ' ' . ($dashboardData['tipe'] ?? '')) }}
                        </p>
                    </div>

                    {{-- Jenis BBM --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Jenis BBM
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $dashboardData['bbm'] ?? '-' }}
                        </p>
                    </div>

                    {{-- Nama pengemudi --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Nama Pengemudi
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $dashboardData['nama_pengemudi'] ?? '-' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===================================================== --}}
        {{-- CARD 2 : KENDARAAN AKTIF ANDA --}}
        {{-- ===================================================== --}}
        <div class="xl:col-span-7 flex flex-col">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] h-full flex flex-col">

                {{-- Header card --}}
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Kendaraan Aktif Anda
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Ringkasan status penugasan kendaraan saat ini.
                        </p>
                    </div>

                    {{-- Badge status penugasan --}}
                    @php
                        $status = $dashboardData['status_perjalanan'] ?? '-';

                        $badgeClass = match($status) {
                            'diterbitkan' => 'bg-warning-100 text-warning-700 dark:bg-warning-500/15 dark:text-warning-400',
                            'diterima'    => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
                            'berjalan'    => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                            'selesai'     => 'bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-300',
                            'dibatalkan'  => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                            default       => 'bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-300',
                        };
                    @endphp

                    <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium {{ $badgeClass }}">
                        {{ ucfirst($status) }}
                    </span>
                </div>

                {{-- Detail info tugas --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {{-- KM awal --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            KM Awal Kendaraan
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ number_format((int) ($dashboardData['km_awal'] ?? 0), 0, ',', '.') }} km
                        </p>
                    </div>

                    {{-- Tujuan --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Tujuan Perjalanan
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $dashboardData['tujuan'] ?? '-' }}
                        </p>
                    </div>

                    {{-- Tanggal penerbitan / tanggal tugas --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Tanggal Penerbitan Penugasan
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ !empty($dashboardData['tanggal_tugas']) ? \Carbon\Carbon::parse($dashboardData['tanggal_tugas'])->translatedFormat('d F Y') : '-' }}
                        </p>
                    </div>

                    {{-- Waktu mulai perjalanan --}}
                    <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                        <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Perjalanan Dimulai
                        </p>
                        <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                            {{ $dashboardData['waktu_mulai'] ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- Section Tambahan: Detail Penugasan --}}
                <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-800">
                    <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500 mb-4">
                        Detail Penugasan
                    </h4>
                    <div class="grid grid-cols-1 gap-4">
                        {{-- Catatan Admin --}}
                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/30">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                                Catatan Tambahan (Admin)
                            </p>
                            <p class="mt-1 text-sm italic text-gray-600 dark:text-gray-400">
                                {{ $dashboardData['catatan'] ?? 'Tidak ada catatan tambahan.' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- CTA utama --}}
                <div class="mt-auto pt-6">
                    @php $statusPerjalanan = $dashboardData['status_perjalanan'] ?? '-'; @endphp

                    @if(!empty($dashboardData['penugasan_aktif']) && ($dashboardData['bisa_terima_tugas'] ?? false))
                        {{-- Penugasan baru diterbitkan, perlu diterima --}}
                        <form
                            action="{{ route('kendaraan.penugasan.terima', $dashboardData['penugasan_aktif']->id) }}"
                            method="POST"
                        >
                            @csrf
                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center rounded-xl px-5 py-3 text-sm font-medium bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400 hover:bg-success-600 hover:text-white dark:hover:text-white dark:hover:bg-success-600 md:w-auto"
                            >
                                Terima Tugas
                            </button>
                        </form>

                    @elseif($statusPerjalanan === 'diterima')
                        {{-- Tugas sudah diterima, menunggu mulai perjalanan --}}
                        <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 dark:border-blue-800/50 dark:bg-blue-500/10">
                            <p class="font-semibold text-blue-800 dark:text-blue-300 text-sm">Tugas Telah Diterima</p>
                            <p class="mt-0.5 text-xs text-blue-600 dark:text-blue-400 mb-3">Silakan mulai perjalanan jika sudah siap.</p>
                            <a
                                href="{{ route('kendaraan.perjalanan-aktif') }}"
                                class="inline-flex items-center gap-2 text-xs font-bold text-blue-700 dark:text-blue-300 hover:underline"
                            >
                                Buka Halaman Perjalanan Aktif
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                    @elseif($statusPerjalanan === 'berjalan')
                        {{-- Sedang dalam perjalanan --}}
                        <div class="rounded-xl border border-success-200 bg-success-50 px-4 py-3 dark:border-success-800/50 dark:bg-success-500/10">
                            <p class="font-semibold text-success-800 dark:text-success-300 text-sm">Sedang Dalam Perjalanan</p>
                            <p class="mt-0.5 text-xs text-success-600 dark:text-success-400">Perjalanan aktif sedang berjalan. Gunakan tombol di atas untuk melihat detail.</p>
                        </div>

                    @else
                        {{-- Tidak ada penugasan sama sekali --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900/50 dark:text-gray-400">
                            <p class="font-medium text-gray-900 dark:text-white">Tidak Ada Penugasan Baru</p>
                            <p class="mt-0.5 text-xs">Anda tidak memiliki penugasan baru yang harus diterima saat ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection