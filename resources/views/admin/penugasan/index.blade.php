@extends('layouts.app')

@section('content')
<!-- Page Header/Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-title-md2 font-bold text-black dark:text-white">
            Penugasan Kendaraan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="font-medium text-primary dark:text-white">Penugasan Kendaraan</li>
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
            <!-- Search & Filter Area -->
            <form action="{{ route('penugasan.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center w-full xl:w-auto">
                <!-- Search input -->
                <div class="relative w-full sm:w-64">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penugasan..." 
                           class="w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary dark:text-gray-400">
                        <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </div>

                <!-- Filter Status -->
                <select name="status" onchange="this.form.submit()" 
                        class="w-full sm:w-auto rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-700 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-transparent dark:text-gray-300">
                    <option value="Semua" class="text-gray-700" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                    <option value="diterbitkan" class="text-gray-700" {{ request('status') === 'diterbitkan' ? 'selected' : '' }}>Diterbitkan</option>
                    <option value="berjalan" class="text-gray-700" {{ request('status') === 'berjalan' ? 'selected' : '' }}>Berjalan</option>
                    <option value="selesai" class="text-gray-700" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="dibatalkan" class="text-gray-700" {{ request('status') === 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>

                @if(request()->has('search') || request()->has('status'))
                <a href="{{ route('penugasan.index') }}" 
                   class="text-sm font-medium text-error hover:underline whitespace-nowrap dark:text-gray-400">
                    Reset Filter
                </a>
                @endif
            </form>

            <div class="flex items-center gap-3 justify-end">
                <a href="{{ route('penugasan.create') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-sm hover:bg-primary/90 transition-all dark:bg-primary dark:text-white">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Penugasan
                </a>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100 dark:bg-white/5">
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Kendaraan</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Tujuan</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Pengemudi</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Tanggal Tugas</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Status</th>
                    <th class="border-b border-gray-100 px-5 py-4 pr-6 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($penugasans as $tugas)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-gray-800 dark:text-white/90">{{ $tugas->kendaraan->no_polisi ?? '-' }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $tugas->kendaraan->merk ?? '' }} {{ $tugas->kendaraan->tipe ?? '' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ Str::limit($tugas->tujuan, 30) }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $tugas->pengemudi ?: 'Tidak mengisi nama' }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ \Carbon\Carbon::parse($tugas->tgl_tugas)->translatedFormat('d F Y') }}</p>
                    </td>
                    <td class="px-5 py-4">
                        @php
                        $statusConfig = [
                            'diterbitkan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/20 dark:text-warning-400',
                            'berjalan'    => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                            'selesai'     => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
                            'dibatalkan'  => 'bg-error-50 text-error-700 dark:bg-error-500/20 dark:text-error-400',
                        ];
                        $configClass = $statusConfig[$tugas->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $configClass }}">
                            {{ ucfirst($tugas->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-4 pr-6 text-center">
                        <div class="flex items-center justify-center gap-3">
                            <a href="{{ route('penugasan.show', ['penugasan' => $tugas->id ?? $tugas->id_tugas ?? 0]) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-success-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-success-500 dark:hover:text-white" title="Detail">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <circle cx="12" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <a href="{{ route('penugasan.edit', ['penugasan' => $tugas->id ?? $tugas->id_tugas ?? 0]) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-warning-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-warning-500 dark:hover:text-white" title="Edit">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            @if(!in_array($tugas->status, ['selesai', 'dibatalkan']))
                            <button @click="$dispatch('open-cancel-modal', { url: '{{ route('penugasan.batalkan', ['penugasan' => $tugas->id ?? $tugas->id_tugas ?? 0]) }}', title: '{{ $tugas->tujuan }}' })" type="button" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-error-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-error-500 dark:hover:text-white" title="Batalkan">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="10" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="15" y1="9" x2="9" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="9" y1="9" x2="15" y2="15" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data penugasan ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($penugasans->hasPages())
    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
        {{ $penugasans->links() }}
    </div>
    @endif
</div>

<!-- Modal Batalkan -->
<div x-data="{ open: false, url: '', title: '' }"
    @open-cancel-modal.window="open = true; url = $event.detail.url; title = $event.detail.title"
    x-show="open"
    class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 px-4 py-5"
    style="display: none;"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0">
    <div @click.outside="open = false"
        class="w-full max-w-[500px] rounded-2xl bg-white p-8 shadow-2xl dark:bg-gray-900"
        x-transition:enter="transition-transform ease-out duration-300"
        x-transition:enter-start="scale-95"
        x-transition:enter-end="scale-100">
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Konfirmasi Pembatalan</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Apakah Anda yakin ingin membatalkan penugasan ke <span class="font-bold text-gray-900 dark:text-gray-200" x-text="title"></span>? Tindakan ini tidak dapat diurungkan.</p>

        <div class="flex items-center justify-end gap-3">
            <button @click="open = false" type="button" class="rounded-lg bg-gray-100 px-6 py-2.5 font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                Tutup
            </button>
            <form :action="url" method="POST">
                @csrf
                <button type="submit" class="rounded-lg bg-error px-6 py-2.5 font-medium text-white hover:bg-opacity-90 transition-all">
                    Batalkan Penugasan
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
