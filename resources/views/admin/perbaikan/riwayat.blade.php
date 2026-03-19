@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Riwayat Perbaikan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="font-medium text-primary dark:text-white">Riwayat Perbaikan</li>
        </ol>
    </nav>
</div>

<!-- Main Content Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Card Header -->
    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('perbaikan.aktif') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                    Perbaikan Aktif
                </a>
            </div>

            <!-- Search & Filter -->
            <form action="{{ route('perbaikan.riwayat') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari Nopol atau Keluhan..."
                        class="w-full sm:w-64 rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </span>
                </div>

                <button type="submit" class="inline-flex h-9 items-center justify-center rounded-lg bg-gray-100 px-4 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                    Cari
                </button>

                <select name="per_page" onchange="this.form.submit()"
                    class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    @foreach([10, 25, 50, 100] as $val)
                    <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} data</option>
                    @endforeach
                </select>

                @if($search)
                <a href="{{ route('perbaikan.riwayat') }}" class="text-sm text-gray-500 hover:text-primary dark:text-gray-400">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 dark:bg-white/5">
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kendaraan</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Keluhan</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tgl Mulai</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tgl Selesai</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-right">Biaya</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($perbaikans as $perbaikan)
                <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-gray-900 dark:text-white">{{ $perbaikan->kendaraan->no_polisi }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $perbaikan->kendaraan->merk }} {{ $perbaikan->kendaraan->tipe }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                        <p class="line-clamp-1" title="{{ $perbaikan->keluhan }}">{{ $perbaikan->keluhan }}</p>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($perbaikan->tgl_mulai)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                        {{ \Carbon\Carbon::parse($perbaikan->tgl_selesai)->translatedFormat('d F Y') }}
                    </td>
                    <td class="px-5 py-4 text-sm text-right font-bold text-gray-900 dark:text-white">
                        Rp {{ number_format($perbaikan->biaya, 0, ',', '.') }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="flex h-8 w-8 mx-auto items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-success-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400" title="Detail">
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
@endsection