@props(['penugasanTerbaru' => []])

<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-5 pt-5 sm:px-6 sm:pt-6">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
            Aktivitas Penugasan Terbaru
        </h3>
    </div>

    <div class="p-5 sm:p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">No. Polisi</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Merk</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Sopir</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal</th>
                        <th class="pb-3 text-sm font-medium text-gray-500 dark:text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @foreach($penugasanTerbaru as $tugas)
                    <tr>
                        <td class="py-4 text-sm font-medium text-gray-800 dark:text-white/90">
                            {{ $tugas->kendaraan->no_polisi ?? '-' }}
                        </td>
                        <td class="py-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $tugas->kendaraan->merk ?? '-' }}
                        </td>
                        <td class="py-4 text-sm text-gray-500 dark:text-gray-400">
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
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
