@extends('layouts.app')

@section('content')
<!-- Page Header/Breadcrumb -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            Halaman Manajemen Kendaraan
        </h2>
    </div>

    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li class="font-medium text-primary dark:text-white">Master Kendaraan</li>
        </ol>
    </nav>
</div>

<!-- Alert Success -->
@if(session('success'))
<div class="mb-6 flex w-full border-l-6 border-success bg-success/10 px-7 py-4 shadow-md dark:bg-success/20 dark:border-success-500 rounded-lg">
    <div class="mr-5 flex h-9 w-full max-w-9 items-center justify-center rounded-lg bg-success/20">
        <svg class="text-success" width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M11.6667 4.54546L5.65685 10.5553L2.82843 7.72688" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>
    <div class="w-full">
        <h5 class="mb-1 text-lg font-bold text-success">Berhasil</h5>
        <p class="text-base text-gray-600 dark:text-gray-300">{{ session('success') }}</p>
    </div>
</div>
@endif

<!-- Main Content Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <!-- Card Header -->
    <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
        <!-- Search & Filter Area -->
        <form action="{{ route('kendaraan.index') }}" method="GET" class="flex flex-col gap-3 sm:flex-row sm:items-center w-full xl:w-auto">
            <!-- Search input -->
            <div class="relative w-full sm:w-64">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Polisi, Merk, Tipe..." 
                       class="w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-4 pr-10 text-sm outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white">
                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-primary dark:text-gray-400">
                    <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>

            <!-- Filter Status -->
            <select name="status" onchange="this.form.submit()" 
                    class="w-full sm:w-auto rounded-lg border border-gray-300 bg-white py-2.5 px-4 text-sm text-gray-700 outline-none focus:border-primary focus-visible:shadow-none dark:border-strokedark dark:bg-transparent dark:text-gray-300">
                <option value="Semua" class="text-gray-700" {{ request('status') === 'Semua' ? 'selected' : '' }}>Semua Status</option>
                <option value="Tersedia" class="text-gray-700" {{ request('status') === 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Dipakai" class="text-gray-700" {{ request('status') === 'Dipakai' ? 'selected' : '' }}>Dipakai</option>
                <option value="Perbaikan" class="text-gray-700" {{ request('status') === 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
            </select>

            @if(request()->has('search') || request()->has('status'))
            <a href="{{ route('kendaraan.index') }}" 
               class="text-sm font-medium text-error hover:underline whitespace-nowrap dark:text-gray-400">
                Reset Filter
            </a>
            @endif
        </form>

        <!-- Actions - Adjusted to match input height -->
        <div class="flex items-center gap-3 justify-end">
            <a href="{{ route('kendaraan.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-2 py-0.5 text-sm font-medium text-white shadow-theme-sm hover:bg-brand-600 transition-all">
                <svg class="h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Tambah Kendaraan
            </a>
        </div>
    </div>
</div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-100 dark:bg-white/5">
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">No. Polisi</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Merk & Tipe</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300 hidden sm:table-cell">Tahun & Kategori</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">KM Terakhir</th>
                    <th class="border-b border-gray-100 px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300">Status</th>
                    <th class="border-b border-gray-100 px-5 py-4 pr-6 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:border-gray-800 dark:text-gray-300 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                @forelse($kendaraans as $kendaraan)
                <tr class="hover:bg-gray-50/50 dark:hover:bg-white/[0.02] transition-colors">
                    <td class="px-5 py-4">
                        <p class="text-sm font-bold text-gray-800 dark:text-white/90">{{ $kendaraan->no_polisi }}</p>
                    </td>
                    <td class="px-5 py-4">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ $kendaraan->merk }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $kendaraan->tipe }}</p>
                    </td>
                    <td class="px-5 py-4 hidden sm:table-cell">
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $kendaraan->tahun }}</p>
                        <div class="mt-1 flex gap-1">
                            <span class="inline-flex rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $kendaraan->kategori_kendaraan }}</span>
                            <span class="inline-flex rounded bg-gray-100 px-1.5 py-0.5 text-[10px] font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">{{ $kendaraan->jenis_kendaraan }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="inline-flex rounded-lg bg-gray-50 px-2 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                            {{ number_format($kendaraan->km_terakhir, 0, ',', '.') }} KM
                        </span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                        $statusConfig = [
                        'Tersedia' => 'bg-success-50 text-success-700 dark:bg-success-500/20 dark:text-success-400',
                        'Dipakai' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400',
                        'Perbaikan' => 'bg-error-50 text-error-700 dark:bg-error-500/20 dark:text-error-400',
                        ];
                        $configClass = $statusConfig[$kendaraan->status] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300';
                        @endphp
                        <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium {{ $configClass }}">
                            {{ $kendaraan->status }}
                        </span>
                    </td>
                    <td class="px-5 py-4 pr-6 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('kendaraan.edit', $kendaraan->id_kend) }}" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-primary hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-primary dark:hover:text-white" title="Edit">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </a>
                            <button @click="$dispatch('open-delete-modal', { url: '{{ route('kendaraan.destroy', $kendaraan->id_kend) }}', title: '{{ $kendaraan->no_polisi }}' })" type="button" class="flex h-8 w-8 items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-error hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-error dark:hover:text-white" title="Hapus">
                                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3 6h18" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="10" y1="11" x2="10" y2="17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <line x1="14" y1="11" x2="14" y2="17" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                        Tidak ada data kendaraan ditemukan.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($kendaraans->hasPages())
    <div class="border-t border-gray-100 p-5 dark:border-gray-800">
        {{ $kendaraans->links() }}
    </div>
    @endif
</div>

<!-- Modal Hapus -->
<div x-data="{ open: false, url: '', title: '' }"
    @open-delete-modal.window="open = true; url = $event.detail.url; title = $event.detail.title"
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
        <h3 class="text-xl font-bold text-gray-800 dark:text-white mb-2">Konfirmasi Hapus</h3>
        <p class="text-gray-600 dark:text-gray-400 text-sm mb-6">Apakah Anda yakin ingin menghapus data kendaraan <span class="font-bold text-gray-900 dark:text-gray-200" x-text="title"></span>? Tindakan ini tidak dapat dibatalkan.</p>

        <div class="flex items-center justify-end gap-3">
            <button @click="open = false" type="button" class="rounded-lg bg-gray-100 px-6 py-2.5 font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all">
                Batal
            </button>
            <form :action="url" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-lg bg-error px-6 py-2.5 font-medium text-white hover:bg-opacity-90 transition-all">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>
@endsection