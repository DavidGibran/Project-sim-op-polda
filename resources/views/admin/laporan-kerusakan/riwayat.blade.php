@extends('layouts.app')

@section('content')
<!-- Page Header / Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Riwayat Kerusakan
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">Arsip laporan kerusakan yang telah selesai diproses</p>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="#">Kerusakan /</a></li>
            <li class="font-medium text-primary dark:text-white">Riwayat</li>
        </ol>
    </nav>
</div>

<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Table Toolbar -->
    <div class="flex flex-wrap items-center justify-between gap-4 border-b border-gray-100 px-6 py-5 dark:border-gray-800">
        <div class="flex flex-wrap items-center gap-4">
            <!-- Search -->
            <form action="{{ route('admin.laporan-kerusakan.riwayat') }}" method="GET" class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2">
                    <svg class="text-gray-400" width="18" height="18" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.5 17.5L14.5834 14.5833M16.6667 9.58333C16.6667 13.4954 13.4954 16.6667 9.58333 16.6667C5.67131 16.6667 2.5 13.4954 2.5 9.58333C2.5 5.67131 5.67131 2.5 9.58333 2.5C13.4954 2.5 16.6667 5.67131 16.6667 9.58333Z" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="No Laporan / Plat..." class="w-full rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm text-gray-700 outline-none transition focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white">
            </form>
        </div>
    </div>

    <!-- Table -->
    <div class="max-w-full overflow-x-auto">
        <table class="w-full table-auto">
            <thead class="bg-gray-50/50 dark:bg-white/5">
                <tr class="text-left">
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">No. Laporan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Kendaraan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Keluhan</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Tgl Selesai</th>
                    <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($laporans as $laporan)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $laporan->no_laporan }}</div>
                        <div class="text-xs text-gray-500">Lapor: {{ $laporan->tanggal_lapor->format('d/m/Y') }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $laporan->kendaraan->no_polisi }}</div>
                        <div class="text-xs text-gray-500">{{ $laporan->kendaraan->merk }} {{ $laporan->kendaraan->tipe }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-1">{{ $laporan->keluhan }}</p>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-300">
                        {{ $laporan->updated_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.laporan-kerusakan.show', $laporan->id) }}" class="p-2 text-gray-400 hover:text-brand-500 dark:hover:text-brand-400" title="Lihat Detail">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" stroke-width="2"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" stroke-width="2"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <svg class="w-12 h-12 text-gray-200 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="2"/></svg>
                            <span>Belum ada riwayat kerusakan yang selesai.</span>
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
