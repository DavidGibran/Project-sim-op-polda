@props(['perbaikanTerbaru'])

<div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03] sm:p-6">
    <div class="mb-5 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Riwayat Perbaikan Terbaru
        </h3>
    </div>

    <div class="space-y-4">
        @forelse($perbaikanTerbaru as $perbaikan)
            <div class="flex items-start gap-4 rounded-xl bg-gray-50 p-4 transition-colors hover:bg-gray-100 dark:bg-white/[0.03] dark:hover:bg-white/[0.05]">
                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/15 dark:text-red-500">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                    </svg>
                </div>
                <div class="flex flex-1 flex-col gap-1">
                    <p class="text-sm font-bold text-gray-800 dark:text-white/90">
                        {{ $perbaikan->kendaraan->no_polisi ?? 'N/A' }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Dilaporkan {{ \Carbon\Carbon::parse($perbaikan->tanggal_lapor)->translatedFormat('d F Y') }}
                    </p>
                    <div class="mt-1">
                        <span class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium 
                            @if($perbaikan->status == 'Diproses') bg-warning-50 text-warning-600 dark:bg-warning-500/15 dark:text-warning-500
                            @elseif($perbaikan->status == 'Selesai') bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500
                            @else bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-400 @endif">
                            Status: {{ $perbaikan->status }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-10 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada riwayat perbaikan kendaraan.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-6 border-t border-gray-100 pt-4 dark:border-gray-800">
        <a href="{{ route('perbaikan.index') }}" class="flex items-center justify-center gap-1 text-sm font-medium text-gray-500 transition-colors hover:text-primary dark:text-gray-300 dark:hover:text-white">
            Lihat semua
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12h14"></path>
                <path d="m12 5 7 7-7 7"></path>
            </svg>
        </a>
    </div>
</div>
