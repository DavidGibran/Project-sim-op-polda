@props(['kendaraanTidakDigunakan' => []])

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] h-full">
    <div class="px-5 pt-5 sm:px-6 sm:pt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Kendaraan Tidak Digunakan
        </h3>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Daftar kendaraan yang paling jarang digunakan operasional.
        </p>
    </div>

    <div class="p-5 sm:p-6 lg:p-7.5">
        <div class="flex flex-col gap-5 max-h-[450px] overflow-y-auto custom-scrollbar pr-2">
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
                                <span class="inline-flex rounded border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-white/5 px-2 py-0.5 text-[11px] font-medium text-gray-600 dark:text-gray-400">
                                    Belum pernah digunakan
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                    <div class="text-gray-300 dark:text-gray-600 mb-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9c-.1.3-.2.6-.2.9V16c0 .6.4 1 1 1h2"></path><circle cx="7" cy="17" r="2"></circle><circle cx="17" cy="17" r="2"></circle></svg>
                    </div>
                    <p class="text-sm text-gray-400">Semua kendaraan aktif digunakan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
