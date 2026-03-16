@props(['kendaraanTidakDigunakan' => []])

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] h-full">
    <div class="px-5 pt-5 sm:px-6 sm:pt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Kendaraan Tidak Digunakan Terlama
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Daftar kendaraan yang paling jarang digunakan operasional.
        </p>
    </div>

    <div class="p-5 sm:p-6 lg:p-7.5">
        <div class="flex flex-col gap-5">
            @forelse($kendaraanTidakDigunakan as $kendaraan)
                <div class="flex items-start gap-4 p-4 rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02]">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-300">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V14a1 1 0 01-1 1H13z" />
                        </svg>
                    </div>
                    
                    <div class="flex flex-col gap-2">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-800 dark:text-white/90">
                                {{ $kendaraan->no_polisi }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ $kendaraan->merk }} {{ $kendaraan->tipe }}
                            </p>
                        </div>
                        <div class="mt-1">
                            @if($kendaraan->tgl_terakhir)
                                @php
                                    $days = (int) \Carbon\Carbon::parse($kendaraan->tgl_terakhir)->diffInDays(now());
                                @endphp
                                <span class="inline-flex rounded-lg bg-gray-100 dark:bg-white/10 px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ $days }} Hari Lalu
                                </span>
                                
                            @else
                                <span class="inline-flex rounded-lg bg-gray-100 dark:bg-white/10 px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Belum pernah digunakan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-10 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tidak ada data kendaraan.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
