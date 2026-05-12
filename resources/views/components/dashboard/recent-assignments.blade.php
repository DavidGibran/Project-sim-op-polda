@props(['penugasanTerbaru' => []])

<div class="h-full rounded-3xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-gray-900 shadow-sm flex flex-col">
    <div class="px-6 py-5 border-b border-gray-100 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-3">
            <span class="relative flex h-2.5 w-2.5">
              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-warning-500 opacity-75"></span>
              <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-warning-500"></span>
            </span>
            Live Feed Penugasan
        </h3>
        <a href="{{ route('penugasan.index') }}" class="text-[11px] font-bold text-warning-600 hover:text-warning-500 uppercase tracking-wider transition-colors">Lihat Semua</a>
    </div>

    <div class="p-4 sm:p-5 flex-1">
        <div class="flex flex-col gap-3 max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
            @forelse($penugasanTerbaru as $tugas)
                <div class="p-4 rounded-2xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-white/[0.02] hover:bg-gray-50 dark:hover:bg-white/[0.04] transition-colors relative overflow-hidden group">
                    @php
                        $statusStyles = [
                            'diterbitkan' => ['bg-warning-50 text-warning-700 dark:bg-warning-500/10 dark:text-warning-400 border-warning-200 dark:border-warning-500/20', 'bg-warning-500', ''],
                            'diterima' => ['bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400 border-blue-200 dark:border-blue-500/20', 'bg-blue-500', ''],
                            'berjalan' => ['bg-indigo-50 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20', 'bg-indigo-500', 'animate-pulse bg-indigo-500'],
                            'selesai' => ['bg-success-50 text-success-600 dark:bg-success-500/10 dark:text-success-400 border-success-200 dark:border-success-500/20', 'bg-success-500', ''],
                            'dibatalkan' => ['bg-error-50 text-error-600 dark:bg-error-500/10 dark:text-error-400 border-error-200 dark:border-error-500/20', 'bg-error-500', ''],
                        ];
                        $currentStatus = strtolower($tugas->status);
                        $config = $statusStyles[$currentStatus] ?? $statusStyles['diterbitkan'];
                    @endphp

                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $config[1] }} {{ $currentStatus == 'selesai' ? 'opacity-50' : '' }}"></div>
                    
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex items-center gap-2.5 min-w-0 flex-1 mr-2">
                            <span class="text-sm font-black text-gray-900 dark:text-white shrink-0">{{ $tugas->kendaraan->no_polisi ?? 'N/A' }}</span>
                            @php $merkTipe = trim(($tugas->kendaraan->merk ?? '') . ' ' . ($tugas->kendaraan->tipe ?? '')); @endphp
                            <span class="text-[9px] font-bold text-gray-500 uppercase px-2 py-0.5 rounded bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-sm truncate max-w-[120px]" title="{{ $merkTipe }}">{{ $merkTipe ?: 'N/A' }}</span>
                        </div>
                        
                        <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-0.5 text-[10px] font-bold border {{ $config[0] }}">
                            @if($config[2]) <span class="w-1.5 h-1.5 rounded-full {{ $config[2] }}"></span> @endif
                            {{ ucfirst($tugas->status) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-2">
                        <div class="flex items-center gap-1.5 text-[11px] font-medium text-gray-600 dark:text-gray-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="opacity-70"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            <span class="truncate max-w-[120px]">{{ $tugas->pengemudi }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 dark:text-gray-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                            {{ \Carbon\Carbon::parse($tugas->tgl_tugas)->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 text-center border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl h-full">
                    <p class="text-sm font-bold text-gray-500">Tidak ada aktivitas penugasan terbaru</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
