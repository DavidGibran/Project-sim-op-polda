@props(['perbaikanTerbaru'])

<div class="h-full rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-sm flex flex-col">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-3">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error-500 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-error-500"></span>
            </span>
            Live Feed Perbaikan
        </h3>
        <a href="{{ route('perbaikan.index') }}" class="text-[11px] font-bold text-error-600 hover:text-error-500 uppercase tracking-wider transition-colors">Lihat Semua</a>
    </div>

    <div class="p-4 sm:p-5 flex-1">
        <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
            @forelse($perbaikanTerbaru as $perbaikan)
                <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02] hover:bg-gray-50 dark:hover:bg-white/[0.04] transition-colors relative overflow-hidden group">
                    @php
                        $statusStyles = [
                            'dilaporkan' => ['bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400 border-warning-200 dark:border-warning-500/20', 'bg-warning-500', ''],
                            'diproses' => ['bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 border-blue-200 dark:border-blue-500/20', 'bg-blue-500', 'animate-pulse bg-blue-500'],
                            'selesai' => ['bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400 border-success-200 dark:border-success-500/20', 'bg-success-500', ''],
                        ];
                        $currentStatus = strtolower($perbaikan->status);
                        $config = $statusStyles[$currentStatus] ?? $statusStyles['dilaporkan'];
                    @endphp

                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $config[1] }} {{ $currentStatus == 'selesai' ? 'opacity-50' : '' }}"></div>
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1 mr-2">
                            <span class="text-sm font-black text-gray-900 dark:text-white shrink-0">{{ $perbaikan->kendaraan->no_polisi ?? 'N/A' }}</span>
                            @php $merkTipe = trim(($perbaikan->kendaraan->merk ?? '') . ' ' . ($perbaikan->kendaraan->tipe ?? '')); @endphp
                            <span class="text-[9px] font-bold text-gray-500 uppercase px-2 py-0.5 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm truncate max-w-[120px]" title="{{ $merkTipe }}">{{ $merkTipe ?: 'N/A' }}</span>
                        </div>
                        
                        <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[10px] font-bold border {{ $config[0] }}">
                            @if($config[2]) <span class="w-1.5 h-1.5 rounded-full {{ $config[2] }}"></span> @endif
                            {{ ucfirst($perbaikan->status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path></svg>
                            <span class="truncate max-w-[150px]">{{ $perbaikan->keluhan ?? 'Pemeliharaan Rutin' }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 dark:text-gray-400/70">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            {{ \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl h-full">
                    <p class="text-sm font-bold text-gray-500">Tidak ada aktivitas perbaikan terbaru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
