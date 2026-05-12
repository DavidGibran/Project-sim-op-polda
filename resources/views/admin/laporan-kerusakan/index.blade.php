@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Daftar Kerusakan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Monitoring laporan kerusakan aktif (Diterbitkan & Diproses)</p>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="#">Kerusakan /</a></li>
            <li class="font-medium text-primary dark:text-white">Daftar</li>
        </ol>
    </nav>
</div>

<!-- Alert Messages -->
@if(session('success'))
<div class="mb-6 flex w-full border-l-6 border-success-500 bg-success/10 px-7 py-4 shadow-md dark:bg-success/20 dark:border-success-500 rounded-lg">
    <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-success/20">
        <svg class="text-success-500" width="26" height="26" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6667 4.54546L5.65685 10.5553L2.82843 7.72688" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="w-full">
        <h5 class="mb-1 text-lg font-bold text-success-500">Berhasil</h5>
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

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Table Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <form action="{{ route('admin.laporan-kerusakan.index') }}" method="GET" class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2">
                    <svg class="text-gray-400" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.5 17.5L14.5834 14.5833M16.6667 9.58333C16.6667 13.4954 13.4954 16.6667 9.58333 16.6667C5.67131 16.6667 2.5 13.4954 2.5 9.58333C2.5 5.67131 5.67131 2.5 9.58333 2.5C13.4954 2.5 16.6667 5.67131 16.6667 9.58333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search }}" placeholder="No Laporan / Plat..." class="w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </form>

            <!-- Filter Status -->
            <form action="{{ route('admin.laporan-kerusakan.index') }}" method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Status Aktif</option>
                    <option value="diterbitkan" {{ $status == 'diterbitkan' ? 'selected' : '' }}>Diterbitkan</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                </select>
                
                <select name="mode" onchange="this.form.submit()" class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
                    <option value="">Semua Mode Input</option>
                    <option value="simple" {{ $mode == 'simple' ? 'selected' : '' }}>Simpel</option>
                    <option value="detail" {{ $mode == 'detail' ? 'selected' : '' }}>Detail</option>
                </select>

                @if($search) <input type="hidden" name="search" value="{{ $search }}"> @endif
            </form>
        </div>

        <a href="{{ route('admin.laporan-kerusakan.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Buat Laporan Kerusakan
        </a>
    </div>

    <!-- Table -->
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50/50 dark:bg-white/5">
                <tr class="text-left">
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Laporan</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kendaraan</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 w-full">Keluhan</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Status</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Mode</th>
                    <th class="px-4 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($laporans as $laporan)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-4 py-4 whitespace-nowrap">
                        @php
                            $noLaporanFull = $laporan->no_laporan;
                            $noLaporanShort = $noLaporanFull;
                            if (strpos($noLaporanFull, '-') !== false) {
                                $parts = explode('-', $noLaporanFull);
                                if (count($parts) >= 3) {
                                    $day = substr($parts[1], 6, 2);
                                    $month = substr($parts[1], 4, 2);
                                    $shortSeq = substr($parts[2], -2);
                                    $noLaporanShort = "REP-{$day}{$month}{$shortSeq}";
                                }
                            }
                        @endphp
                        <div class="text-sm font-bold text-gray-900 dark:text-white" title="{{ $noLaporanFull }}">{{ $noLaporanShort }}</div>
                        <div class="text-xs text-gray-500">{{ $laporan->tanggal_lapor->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $laporan->kendaraan->no_polisi }}</div>
                        @php $merkTipe = trim(($laporan->kendaraan->merk ?? '') . ' ' . ($laporan->kendaraan->tipe ?? '')); @endphp
                        <div class="text-xs text-gray-500 truncate max-w-[120px]" title="{{ $merkTipe }}">{{ $merkTipe }}</div>
                    </td>
                    <td class="px-4 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2">{{ $laporan->keluhan }}</p>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @php
                            $statusConfig = [
                                'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400 ring-1 ring-warning-500/30',
                                'diproses'    => 'bg-blue-50 text-blue-700 dark:bg-blue-800/20 dark:text-blue-400 ring-1 ring-blue-500/30',
                            ];
                            $configClass = $statusConfig[$laporan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $configClass }}">
                            {{ ucfirst($laporan->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap">
                        @if($laporan->mode == 'detail')
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-blue-600 dark:text-blue-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                                Detail
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-xs font-medium text-warning-600 dark:text-warning-400">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                                Simpel
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.laporan-kerusakan.show', $laporan->id) }}" class="p-2 text-gray-400 hover:text-brand-500 dark:hover:text-brand-400" title="Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                            </a>
                            <a href="{{ route('admin.laporan-kerusakan.edit', $laporan->id) }}" class="p-2 text-gray-400 hover:text-blue-500" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" stroke-width="2"/></svg>
                            </a>
                            @if($laporan->status == 'diterbitkan')
                            <form action="{{ route('admin.laporan-kerusakan.destroy', $laporan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus laporan ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-gray-400 hover:text-error" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </button>
                            </form>
                            @endif


                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-200 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" stroke-width="2"/></svg>
                            <span>Tidak ada laporan kerusakan aktif.</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($laporans->hasPages())
    <div class="border-t border-gray-100 px-6 py-4 dark:border-gray-800">
        {{ $laporans->links() }}
    </div>
    @endif
</div>
@endsection
