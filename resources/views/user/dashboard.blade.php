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
        <div class="xl:col-span-5">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                
                {{-- Header card --}}
                <div class="mb-5 flex items-start justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Informasi Kendaraan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Data kendaraan yang sedang login.
                        </p>
                    </div>

                    {{-- 
                        Tombol laporkan kendala
                        Untuk sekarang nomor WA dikosongkan dulu sesuai instruksi Anda.
                        Nanti tinggal isi nomor admin pada href.
                    --}}
                    <a
                        href="https://wa.me/"
                        target="_blank"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-warning-500 px-4 py-2 text-sm font-medium text-white hover:opacity-90"
                    >
                        Laporkan Kendala
                    </a>
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
        <div class="xl:col-span-7">
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

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
                            'diterbitkan' => 'bg-blue-100 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400',
                            'berjalan' => 'bg-green-100 text-green-700 dark:bg-green-500/15 dark:text-green-400',
                            'selesai' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-300',
                            'dibatalkan' => 'bg-red-100 text-red-700 dark:bg-red-500/15 dark:text-red-400',
                            default => 'bg-gray-100 text-gray-700 dark:bg-gray-500/15 dark:text-gray-300',
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

                {{-- CTA utama --}}
                <div class="mt-6">
                    @if(!empty($dashboardData['penugasan_aktif']) && ($dashboardData['bisa_terima_tugas'] ?? false))
                        {{-- 
                            Tombol aktif jika ada penugasan dengan status diterbitkan
                            Saat diklik akan mengubah status tugas menjadi berjalan
                        --}}
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
                    @else
                        {{-- 
                            Tombol disabled jika tidak ada tugas yang diterbitkan
                        --}}
                        <button
                            type="button"
                            disabled
                            class="inline-flex w-full cursor-not-allowed items-center justify-center rounded-xl bg-gray-300 px-5 py-3 text-sm font-medium text-white dark:bg-gray-700 md:w-auto"
                        >
                            Terima Tugas
                        </button>

                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            Tidak ada penugasan yang bisa diterima saat ini.
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection