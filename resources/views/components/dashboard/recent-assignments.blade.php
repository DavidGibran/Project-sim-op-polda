@props(['penugasanTerbaru' => []])

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03] h-full">
    <div class="px-5 pt-5 sm:px-6 sm:pt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Aktivitas Penugasan Terbaru
        </h3>
    </div>

    <div class="p-5 sm:p-6">
        <div class="overflow-x-auto max-h-[400px] overflow-y-auto custom-scrollbar pr-2">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. Polisi</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tipe</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Pengemudi</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($penugasanTerbaru as $tugas)
                    <tr>
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-800 dark:text-white/90">{{ $tugas->kendaraan->no_polisi ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-sm text-gray-800 dark:text-gray-300">
                            {{ $tugas->kendaraan->tipe ?? 'N/A' }}
                        </td>
                        <td class="py-4 text-sm text-gray-800 dark:text-gray-300">
                            {{ $tugas->nama_sopir }}
                        </td>
                        <td class="py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($tugas->tgl_tugas)->translatedFormat('d M Y') }}
                        </td>
                        <td class="py-4">
                            @php
                                $statusClasses = [
                                    'diterbitkan' => 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300',
                                    'diterima' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-500',
                                    'berjalan' => 'bg-warning-50 text-warning-700 dark:bg-warning-500/15 dark:text-warning-500',
                                    'selesai' => 'bg-success-50 text-success-700 dark:bg-success-500/15 dark:text-success-500',
                                    'dibatalkan' => 'bg-error-50 text-error-700 dark:bg-error-500/15 dark:text-error-500',
                                ];
                                $class = $statusClasses[$tugas->status] ?? 'bg-gray-100 text-gray-700';
                            @endphp
                            <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $class }}">
                                {{ ucfirst($tugas->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-10 text-center">
                            <p class="text-sm text-gray-400">Tidak ada data penugasan terbaru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="px-5 pb-5">
        <div class="border-t border-gray-100 pt-4 dark:border-gray-800">
            <a href="{{ route('penugasan.index') }}" class="flex items-center justify-center gap-1 text-sm font-medium text-gray-500 transition-colors hover:text-primary dark:text-gray-300 dark:hover:text-white">
                Lihat semua
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="m12 5 7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</div>
