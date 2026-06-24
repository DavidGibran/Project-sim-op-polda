@props(['kendaraanTidakDigunakan' => []])

<div class="h-full rounded-3xl border border-warning-200 bg-white dark:border-warning-900/30 dark:bg-gray-900 flex flex-col overflow-hidden shadow-sm">
    <div class="px-6 py-5 border-b border-warning-100 dark:border-warning-900/20 bg-warning-50/50 dark:bg-warning-900/10 flex items-center justify-between">
        <div>
            <h3 class="text-lg font-bold text-warning-800 dark:text-warning-500 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                Perlu Perhatian (Idle)
            </h3>
            <p class="mt-1 text-xs font-medium text-warning-600 dark:text-warning-400/80">
                Prioritaskan armada ini untuk rotasi penugasan.
            </p>
        </div>
    </div>

    <div class="p-5 sm:p-6 flex-1 bg-white dark:bg-transparent">
        <div class="flex flex-col gap-4 max-h-[300px] overflow-y-auto custom-scrollbar pr-2">
            @forelse($kendaraanTidakDigunakan as $kendaraan)
                <div class="flex items-start gap-4 p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02] hover:border-warning-300 dark:hover:border-warning-700/50 transition-colors group">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 shadow-sm border border-gray-100 dark:border-gray-700 group-hover:text-warning-600 dark:group-hover:text-warning-500 transition-colors">
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <div class="flex flex-col gap-1 w-full">
                        <div class="flex justify-between items-start">
                            <h4 class="text-sm font-bold text-gray-900 dark:text-white">
                                {{ $kendaraan->no_polisi }}
                            </h4>
                        </div>
                        <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400 truncate max-w-[150px]" title="{{ $kendaraan->merk }} {{ $kendaraan->tipe }}">
                            {{ $kendaraan->merk }} {{ $kendaraan->tipe }}
                        </p>
                        
                        <div class="mt-2">
                            @if($kendaraan->tgl_terakhir)
                                @php
                                    $days = (int) \Carbon\Carbon::parse($kendaraan->tgl_terakhir)->diffInDays(now());
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-md bg-warning-50 dark:bg-warning-500/10 px-2 py-0.5 text-[10px] font-bold text-warning-700 dark:text-warning-400 border border-warning-200 dark:border-warning-500/20">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                                    Idle {{ $days }} Hari
                                </span>
                            @else
                                <span class="inline-flex rounded-md border border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 px-2 py-0.5 text-[10px] font-bold text-gray-600 dark:text-gray-400">
                                    Belum pernah tugas
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl h-full">
                    <div class="text-success-500 mb-3 bg-success-50 dark:bg-success-500/10 p-3 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                    <p class="text-sm font-bold text-gray-800 dark:text-white">Rotasi Optimal</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Semua kendaraan aktif digunakan secara merata.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
