@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Perbaikan Aktif
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="font-medium text-primary dark:text-white">Perbaikan Aktif</li>
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

<!-- Main Content Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Card Header -->
    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
        <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('perbaikan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-sm hover:bg-brand-600 transition-all">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Laporan
                </a>
                <a href="{{ route('perbaikan.riwayat') }}" class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5 transition-all">
                   Riwayat Perbaikan
                </a>
            </div>

            <!-- Status & Filter -->
            <form action="{{ route('perbaikan.aktif') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <select name="status" onchange="this.form.submit()" 
                    class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Status</option>
                    <option value="dilaporkan" {{ $status == 'dilaporkan' ? 'selected' : '' }}>Dilaporkan</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                </select>

                <select name="per_page" onchange="this.form.submit()" 
                    class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    @foreach([10, 25, 50, 100] as $val)
                        <option value="{{ $val }}" {{ $perPage == $val ? 'selected' : '' }}>{{ $val }} data</option>
                    @endforeach
                </select>

                @if($status)
                    <a href="{{ route('perbaikan.aktif') }}" class="text-sm text-gray-500 hover:text-primary dark:text-gray-400">Reset</a>
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
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tgl Lapor</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">Teknisi</th>
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
                    <td class="px-5 py-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-1">{{ $perbaikan->keluhan }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->translatedFormat('d F Y') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $statusConfig = [
                                'dilaporkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400 ring-1 ring-warning-500/30',
                                'diproses'    => 'bg-blue-50 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400 ring-1 ring-blue-500/30',
                                'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400 ring-1 ring-success-500/30',
                            ];
                            $configClass = $statusConfig[$perbaikan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $configClass }}">
                            {{ ucfirst($perbaikan->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                        {{ $perbaikan->teknisi ?: '-' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-success-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400" title="Detail">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            
                            @if($perbaikan->status === 'dilaporkan')
                            <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="action" value="mulai">
                                <button type="submit" class="flex items-center justify-center rounded-lg bg-blue-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-blue-600 transition-all" title="Mulai Perbaikan">
                                    Mulai
                                </button>
                            </form>
                            @elseif($perbaikan->status === 'diproses')
                            <a href="{{ route('perbaikan.show', $perbaikan->id) }}" class="flex items-center justify-center rounded-lg bg-success-500 px-3 py-1.5 text-xs font-medium text-white hover:bg-success-600 transition-all" title="Selesaikan Perbaikan">
                                Selesai
                            </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada perbaikan aktif.
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
