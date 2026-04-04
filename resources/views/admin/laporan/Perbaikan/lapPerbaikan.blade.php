@extends('layouts.app')

@section('content')
<!-- 
    Page Header / Breadcrumb
    Bagian ini dipertahankan seperti struktur view Anda sebelumnya.
-->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Laporan Perbaikan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li>
                <a
                    class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400"
                    href="{{ route('admin.dashboard') }}"
                >
                    Dashboard /
                </a>
            </li>
            <li class="font-medium text-primary dark:text-white">
                Riwayat Perbaikan
            </li>
        </ol>
    </nav>
</div>

{{-- 
    Chart Section
    Tetap dipertahankan, sesuai permintaan Anda.
--}}
<div class="grid grid-cols-12 gap-4 md:gap-6 2xl:gap-8 mb-6">
    <div class="col-span-12 xl:col-span-8">
        <x-laporan.repair-trend-chart :trendData="$repairTrendChart" />
    </div>

    <div class="col-span-12 xl:col-span-4">
        <x-laporan.repair-status-chart :statusData="$repairStatusChart" />
    </div>
</div>

<!-- Main Content Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

    <!-- 
        Card Header
        Tombol export tetap berada di header tabel/card, bukan dipindah ke atas halaman.
    -->
    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

            <!-- Tombol Export -->
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    onclick="openExportModal()"
                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all"
                >
                    Export
                </button>
            </div>

            <!-- 
                Search & Filter
                Ditambah filter tanggal, tapi struktur search/filter lama tetap dipertahankan.
            -->
            <form action="{{ route('laporan.perbaikan') }}" method="GET" class="flex flex-wrap items-center gap-3">

                <!-- Search -->
                <div class="relative">
                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Cari Nopol atau Keluhan."
                        class="w-full sm:w-64 rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </span>
                </div>

                <!-- Filter Periode -->
                <div class="flex items-center gap-2">
                    <select
                        name="periode"
                        id="filter_periode"
                        class="rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        onchange="toggleCustomDates(this.value)"
                    >
                        <option value="all" {{ ($periode ?? '') == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                        <option value="this_month" {{ ($periode ?? '') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" {{ ($periode ?? '') == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="this_year" {{ ($periode ?? '') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                        <option value="custom" {{ ($periode ?? '') == 'custom' ? 'selected' : '' }}>Kustom Range</option>
                    </select>

                    <div id="custom_date_range" class="{{ ($periode ?? '') == 'custom' ? 'flex' : 'hidden' }} items-center gap-2">
                        <input
                            type="date"
                            name="tanggal_dari"
                            value="{{ $tanggalDari ?? '' }}"
                            class="rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                            title="Tanggal Dari"
                        >
                        <span class="text-gray-400">-</span>
                        <input
                            type="date"
                            name="tanggal_sampai"
                            value="{{ $tanggalSampai ?? '' }}"
                            class="rounded-lg border border-gray-200 bg-transparent py-2 px-3 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                            title="Tanggal Sampai"
                        >
                    </div>
                </div>

                <!-- Tombol cari -->
                <button
                    type="submit"
                    class="inline-flex h-9 items-center justify-center rounded-lg bg-gray-100 px-4 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all"
                >
                    Terapkan
                </button>

                <!-- Reset filter -->
                @if($search || ($periode ?? 'all') !== 'all')
                    <a
                        href="{{ route('laporan.perbaikan') }}"
                        class="text-sm text-gray-500 hover:text-primary dark:text-gray-400"
                    >
                        Reset
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-white/5">
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Kendaraan
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Keluhan
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Tgl Mulai
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                        Tgl Selesai
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">
                        Biaya
                    </th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">
                        Aksi
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($perbaikans as $perbaikan)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                        <td class="px-5 py-4">
                            {{-- No polisi + detail kendaraan --}}
                            <p class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $perbaikan->kendaraan->no_polisi ?? '-' }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $perbaikan->kendaraan->merk ?? '-' }} {{ $perbaikan->kendaraan->tipe ?? '' }}
                            </p>
                        </td>

                        <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                            {{-- Keluhan --}}
                            <p class="line-clamp-1" title="{{ $perbaikan->keluhan }}">
                                {{ $perbaikan->keluhan }}
                            </p>
                        </td>

                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{-- Tanggal mulai --}}
                            {{ $perbaikan->tgl_mulai ? \Carbon\Carbon::parse($perbaikan->tgl_mulai)->translatedFormat('d F Y') : '-' }}
                        </td>

                        <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                            {{-- Tanggal selesai --}}
                            {{ $perbaikan->tgl_selesai ? \Carbon\Carbon::parse($perbaikan->tgl_selesai)->translatedFormat('d F Y') : '-' }}
                        </td>

                        <td class="px-5 py-4 text-sm text-right font-bold text-gray-900 dark:text-white">
                            {{-- Biaya --}}
                            Rp {{ number_format($perbaikan->biaya ?? 0, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4 text-center">
                            {{-- Tombol detail --}}
                            <a
                                href="{{ route('perbaikan.show', $perbaikan->id) }}?from=laporan"
                                class="flex h-8 w-8 mx-auto items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-success-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400"
                                title="Detail"
                            >
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            Belum ada riwayat perbaikan yang selesai.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
        {{ $perbaikans->links() }}
    </div>
</div>

<!-- 
    Modal Export
    Popup ini akan muncul saat tombol Export di header tabel ditekan.
-->
<div id="exportModal" class="fixed inset-0 z-999999 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-lg rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Export Laporan Perbaikan
            </h3>

            <button
                type="button"
                onclick="closeExportModal()"
                class="text-gray-500 hover:text-black dark:hover:text-white"
            >
                ✕
            </button>
        </div>

        <!-- 
            Form export
            Target _blank agar PDF preview terbuka di tab baru.
            Excel juga tetap bisa langsung terdownload.
        -->
        <form id="exportForm" method="GET" target="_blank">
            <div class="mb-4">
                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Periode Laporan
                </label>
                <select
                    name="periode"
                    id="export_periode"
                    class="w-full rounded-lg border border-gray-200 bg-transparent px-3 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    onchange="toggleExportCustomDates(this.value)"
                >
                    <option value="all">Semua Waktu</option>
                    <option value="this_month" selected>Bulan Ini</option>
                    <option value="last_month">Bulan Lalu</option>
                    <option value="this_year">Tahun Ini</option>
                    <option value="custom">Kustom Range</option>
                </select>
            </div>

            <div id="export_custom_date_range" class="hidden grid-cols-1 gap-4 md:grid-cols-2 mb-4">
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tanggal Dari
                    </label>
                    <input
                        type="date"
                        name="tanggal_dari"
                        value="{{ $tanggalDari ?? '' }}"
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-3 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Tanggal Sampai
                    </label>
                    <input
                        type="date"
                        name="tanggal_sampai"
                        value="{{ $tanggalSampai ?? '' }}"
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-3 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                </div>
            </div>

            <!-- 
                Hidden input search
                Supaya hasil export sinkron dengan hasil pencarian di halaman.
            -->
            <input type="hidden" name="search" value="{{ $search ?? '' }}">

            <div class="mt-6 flex flex-wrap justify-end gap-3">
                <button
                    type="button"
                    onclick="submitExport('pdf')"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white hover:opacity-90"
                >
                    Preview PDF
                </button>

                <button
                    type="button"
                    onclick="submitExport('excel')"
                    class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white hover:opacity-90"
                >
                    Export Excel
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    /**
     * Membuka modal export
     */
    function openExportModal() {
        const modal = document.getElementById('exportModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /**
     * Menutup modal export
     */
    function closeExportModal() {
        const modal = document.getElementById('exportModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /**
     * Submit export berdasarkan format yang dipilih
     * 
     * pdf   -> preview PDF
     * excel -> download Excel
     */
    function submitExport(format) {
        const form = document.getElementById('exportForm');

        if (format === 'pdf') {
            form.action = "{{ route('laporan.export.pdf', ['type' => 'perbaikan']) }}";
        } else {
            form.action = "{{ route('laporan.export.excel', ['type' => 'perbaikan']) }}";
        }

        form.submit();
    }

    /**
     * Toggle custom date range inputs
     */
    function toggleCustomDates(value) {
        const customRange = document.getElementById('custom_date_range');
        if (value === 'custom') {
            customRange.classList.remove('hidden');
            customRange.classList.add('flex');
        } else {
            customRange.classList.add('hidden');
            customRange.classList.remove('flex');
        }
    }

    /**
     * Toggle custom date range inputs in export modal
     */
    function toggleExportCustomDates(value) {
        const customRange = document.getElementById('export_custom_date_range');
        if (value === 'custom') {
            customRange.classList.remove('hidden');
            customRange.classList.add('grid');
        } else {
            customRange.classList.add('hidden');
            customRange.classList.remove('grid');
        }
    }

    /**
     * Tutup modal jika user tekan tombol Escape
     */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeExportModal();
        }
    });
</script>
@endsection