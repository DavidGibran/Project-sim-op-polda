@props([
    'totalKendaraan' => 0,
    'kendaraanAktif' => 0,
    'kendaraanPerbaikan' => 0,
    'penugasanAktif' => 0,
    'totalR2' => 0,
    'totalR4' => 0,
    'siapDipakai' => 0,
    'sedangTugas' => 0,
    'perbaikanTerbaru' => [],
    'perbaikanAktif' => [],
    // New Props
    'avgOdometer' => 0,
    'avgAssignmentsPerDay' => 0,
    'oldestVehicle' => null,
    'newestVehicle' => null,
    'utilizationRate' => 0,
    'topVehicles' => [],
    'avgRepairDuration' => 0,
    'oldestRepair' => null,
    'assignmentsToday' => 0,
    'assignmentsFinishedToday' => 0,
    'oldestAssignment' => null,
])

<div x-data="{ 
    showInfo: false, 
    modalType: '',
    openModal(type) {
        this.modalType = type;
        this.showInfo = true;
    }
}">
    <!-- Modal Information -->
    <div x-show="showInfo" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-99999 flex items-center justify-center bg-black/50 px-4 py-5"
         style="display: none;">
        <div @click.outside="showInfo = false" 
             class="w-full max-w-[500px] max-h-[90vh] overflow-y-auto custom-scrollbar rounded-2xl bg-white p-8 shadow-2xl dark:bg-gray-900">
            
            <template x-if="modalType === 'total'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Komposisi Armada</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Gambaran menyeluruh inventaris kendaraan</p>
                        </div>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors bg-gray-100 dark:bg-gray-800 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Composition Bar -->
                        <div>
                            <div class="flex justify-between text-xs font-bold uppercase tracking-wider mb-2">
                                <span class="text-primary dark:text-white">Roda 2 ({{ $totalR2 }})</span>
                                <span class="text-gray-400">Roda 4 ({{ $totalR4 }})</span>
                            </div>
                            <div class="h-3 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex">
                                <div class="h-full bg-primary dark:bg-white transition-all duration-500" style="width: {{ $totalKendaraan > 0 ? ($totalR2 / $totalKendaraan) * 100 : 0 }}%"></div>
                                <div class="h-full bg-gray-300 dark:bg-gray-600 transition-all duration-500" style="width: {{ $totalKendaraan > 0 ? ($totalR4 / $totalKendaraan) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800">
                                <p class="text-[10px] dark:text-white font-bold text-primary uppercase tracking-widest mb-1">Rata-rata Odometer</p>
                                <p class="text-xl font-black text-gray-800 dark:text-white">{{ number_format($avgOdometer, 0, ',', '.') }} <span class="text-xs font-normal">KM</span></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Intensitas Penggunaan</p>
                                <p class="text-xl font-black text-gray-800 dark:text-white">{{ $avgAssignmentsPerDay }} <span class="text-xs font-normal opacity-50">Tugas / Hari</span></p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-4 rounded-xl bg-success-50/50 dark:bg-success-500/5 border border-success-100/50 dark:border-success-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-success-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Siap Operasional</span>
                                </div>
                                <span class="text-sm font-bold text-success-600">{{ $siapDipakai }} Unit</span>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-xl bg-error-50/50 dark:bg-error-500/5 border border-error-100/50 dark:border-error-500/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-error-500"></div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Dalam Perbaikan</span>
                                </div>
                                <span class="text-sm font-bold text-error-600">{{ $kendaraanPerbaikan }} Unit</span>
                            </div>
                        </div>

                        <div class="pt-4 mt-2 border-t border-gray-100 dark:border-gray-800 flex justify-between items-center text-gray-900 dark:text-white">
                            <span class="font-bold uppercase tracking-widest text-xs">Total Kendaraan</span>
                            <span class="text-2xl font-black">{{ $totalKendaraan }} <span class="text-sm font-medium opacity-50">Unit</span></span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'aktif'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Status Operasional</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Efektivitas penggunaan armada saat ini</p>
                        </div>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors bg-gray-100 dark:bg-gray-800 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div class="flex items-center gap-6 p-6 rounded-2xl bg-success-50/30 dark:bg-success-500/5 border border-success-100 dark:border-success-500/10">
                            <div class="relative flex items-center justify-center">
                                <svg class="w-20 h-20 transform -rotate-90">
                                    <circle cx="40" cy="40" r="34" stroke="currentColor" stroke-width="8" fill="transparent" class="text-gray-200 dark:text-gray-800" />
                                    <circle cx="40" cy="40" r="34" stroke="currentColor" stroke-width="8" fill="transparent" 
                                            stroke-dasharray="{{ 2 * pi() * 34 }}" 
                                            stroke-dashoffset="{{ (1 - ($utilizationRate / 100)) * (2 * pi() * 34) }}" 
                                            class="text-success-500 transition-all duration-1000" />
                                </svg>
                                <span class="absolute text-lg font-black text-success-600 dark:text-success-400">{{ $utilizationRate }}%</span>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-success-700 dark:text-success-500 uppercase tracking-widest">Tingkat Utilitas</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                    <span class="font-bold text-gray-900 dark:text-white">{{ $kendaraanAktif }}</span> kendaraan dari total <span class="font-bold text-gray-900 dark:text-white">{{ $totalKendaraan }}</span> sedang aktif.
                                </p>
                            </div>
                        </div>

                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Top 3 Kendaraan Paling Aktif</p>
                            <div class="space-y-2">
                                @foreach($topVehicles as $v)
                                <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 dark:bg-white/[0.02] border border-gray-100 dark:border-gray-800">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-[10px] font-bold text-gray-800 dark:text-white shadow-sm border border-gray-100 dark:border-gray-700 uppercase">
                                            {{ $v->kategori_kendaraan == 'Lainnya' ? 'LN' : $v->kategori_kendaraan }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $v->no_polisi }}</p>
                                            <p class="text-[10px] text-gray-500 dark:text-gray-400">{{ $v->merk }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ $v->penugasans_count }}</p>
                                        <p class="text-[9px] text-gray-400 uppercase">Tugas</p>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'perbaikan'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Manajemen Pemeliharaan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Status perbaikan dan efisiensi teknis</p>
                        </div>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors bg-gray-100 dark:bg-gray-800 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-error-50 dark:bg-error-500/5 border border-error-100 dark:border-error-500/10">
                                <p class="text-[10px] font-bold text-error-600 uppercase tracking-widest mb-1">Dalam Perbaikan</p>
                                <p class="text-2xl font-black text-error-600">{{ $kendaraanPerbaikan }} <span class="text-xs font-medium opacity-70">Unit</span></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-gray-50 dark:bg-white/[0.03] border border-gray-100 dark:border-gray-800">
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Avg. Durasi</p>
                                <p class="text-2xl font-black text-gray-800 dark:text-white">{{ $avgRepairDuration }} <span class="text-xs font-medium opacity-50">Hari</span></p>
                            </div>
                        </div>

                        @if($oldestRepair)
                        <div class="p-4 rounded-xl bg-warning-50/50 dark:bg-warning-500/5 border border-warning-100/50 dark:border-warning-500/10 flex items-start gap-3">
                            <div class="mt-0.5 text-warning-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold text-warning-700 dark:text-warning-500 uppercase tracking-wider">Perbaikan Terlama</p>
                                @php
                                    $startOldest = \Carbon\Carbon::parse($oldestRepair->tanggal_lapor);
                                    $totalMinutes = (int) $startOldest->diffInMinutes(now());
                                    $totalHours   = (int) $startOldest->diffInHours(now());
                                    $totalDays    = (int) $startOldest->diffInDays(now());
                                    if ($totalDays >= 1) {
                                        $durasiOldest = $totalDays . ' hari';
                                    } elseif ($totalHours >= 1) {
                                        $durasiOldest = $totalHours . ' jam';
                                    } else {
                                        $durasiOldest = $totalMinutes . ' menit';
                                    }
                                @endphp
                                <p class="text-xs text-gray-700 dark:text-gray-300 mt-0.5">
                                    <span class="font-bold">{{ $oldestRepair->kendaraan->no_polisi }}</span> telah diperbaiki selama <span class="font-bold text-warning-700 dark:text-warning-400">{{ $durasiOldest }}</span>.
                                </p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Update Perbaikan Terkini</p>
                            <div class="space-y-3 max-h-[350px] overflow-y-auto custom-scrollbar pr-2">
                                @forelse($perbaikanAktif as $p)
                                <div class="p-4 rounded-2xl bg-gray-50/50 dark:bg-white/[0.02] border border-gray-100 dark:border-gray-800 relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-error-500/50"></div>
                                    <div class="flex flex-col gap-1">
                                        <p class="text-sm font-bold text-gray-800 dark:text-white">
                                            {{ $p->kendaraan->no_polisi }}
                                        </p>
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-1">{{ $p->keluhan }}</p>
                                        <div class="flex items-center gap-4 mt-2">
                                            <div class="flex items-center gap-1.5 text-[10px] text-gray-500 uppercase font-medium">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                                {{ \Carbon\Carbon::parse($p->tanggal_lapor)->translatedFormat('d M Y') }}
                                            </div>
                                            <div class="flex items-center gap-1.5 text-[10px] text-error-600 uppercase font-bold">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                                @php
                                                    $startItem = \Carbon\Carbon::parse($p->tanggal_lapor);
                                                    $dDays  = (int) $startItem->diffInDays(now());
                                                    $dHours = (int) $startItem->diffInHours(now());
                                                    $dMins  = (int) $startItem->diffInMinutes(now());
                                                    if ($dDays >= 1) { $durasi = $dDays . ' Hari'; }
                                                    elseif ($dHours >= 1) { $durasi = $dHours . ' Jam'; }
                                                    else { $durasi = $dMins . ' Menit'; }
                                                @endphp
                                                {{ $durasi }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <div class="p-8 text-center rounded-2xl border border-dashed border-gray-200 dark:border-gray-800">
                                    <p class="text-xs text-gray-400">Semua armada dalam kondisi prima</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'tugas'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800 dark:text-white">Aktivitas Penugasan</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Monitoring pergerakan armada harian</p>
                        </div>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors bg-gray-100 dark:bg-gray-800 p-2 rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>

                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 rounded-2xl bg-warning-50/50 dark:bg-warning-500/5 border border-warning-100/50 dark:border-warning-500/10">
                                <p class="text-[10px] font-bold text-warning-600 uppercase tracking-widest mb-1">Mulai Hari Ini</p>
                                <p class="text-2xl font-black text-warning-600">{{ $assignmentsToday }} <span class="text-xs font-medium opacity-70">Tugas</span></p>
                            </div>
                            <div class="p-4 rounded-2xl bg-success-50/50 dark:bg-success-500/5 border border-success-100/50 dark:border-success-500/10">
                                <p class="text-[10px] font-bold text-success-600 uppercase tracking-widest mb-1">Selesai Hari Ini</p>
                                <p class="text-2xl font-black text-success-600">{{ $assignmentsFinishedToday }} <span class="text-xs font-medium opacity-70">Tugas</span></p>
                            </div>
                        </div>

                        @if($oldestAssignment)
                        <div class="p-4 rounded-xl bg-primary/5 dark:bg-white/[0.02] border border-primary/10 dark:border-gray-800 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-primary dark:text-blue-300 uppercase tracking-widest mb-1">Penugasan Terlama</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">{{ $oldestAssignment->kendaraan->no_polisi }} ({{ $oldestAssignment->pengemudi }})</p>
                                @php
                                    $startOldestA = \Carbon\Carbon::parse($oldestAssignment->tgl_tugas);
                                    $totalMinutesA = (int) $startOldestA->diffInMinutes(now());
                                    $totalHoursA   = (int) $startOldestA->diffInHours(now());
                                    $totalDaysA    = (int) $startOldestA->diffInDays(now());
                                    if ($totalDaysA >= 1) { $durasiA = $totalDaysA . ' Hari'; }
                                    elseif ($totalHoursA >= 1) { $durasiA = $totalHoursA . ' Jam'; }
                                    else { $durasiA = $totalMinutesA . ' Menit'; }
                                @endphp
                                <p class="text-[10px] text-gray-500 mt-0.5">Durasi: {{ $durasiA }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full border border-primary/20 dark:border-gray-700 text-xs font-black text-primary dark:text-blue-300">
                                Active
                            </div>
                        </div>
                        @endif

                        <div class="space-y-3 pt-2">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Distribusi Status Saat Ini</p>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex">
                                    <div class="h-full bg-blue-500" style="width: {{ $penugasanAktif > 0 ? ((\App\Models\Penugasan::where('status', 'diterbitkan')->count() / $penugasanAktif) * 100) : 0 }}%"></div>
                                    <div class="h-full bg-warning-500" style="width: {{ $penugasanAktif > 0 ? ((\App\Models\Penugasan::where('status', 'diterima')->count() / $penugasanAktif) * 100) : 0 }}%"></div>
                                    <div class="h-full bg-success-500" style="width: {{ $penugasanAktif > 0 ? ((\App\Models\Penugasan::where('status', 'berjalan')->count() / $penugasanAktif) * 100) : 0 }}%"></div>
                                </div>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center">
                                    <p class="text-[10px] font-bold text-blue-500 uppercase">Diterbitkan</p>
                                    <p class="text-sm font-black text-gray-800 dark:text-white">{{ \App\Models\Penugasan::where('status', 'diterbitkan')->count() }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] font-bold text-warning-500 uppercase">Diterima</p>
                                    <p class="text-sm font-black text-gray-800 dark:text-white">{{ \App\Models\Penugasan::where('status', 'diterima')->count() }}</p>
                                </div>
                                <div class="text-center">
                                    <p class="text-[10px] font-bold text-success-500 uppercase">On-Road</p>
                                    <p class="text-sm font-black text-gray-800 dark:text-white">{{ \App\Models\Penugasan::where('status', 'berjalan')->count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="mt-8 flex justify-end">
                <button @click="showInfo = false" 
                        class="rounded-lg bg-gray-100 px-6 py-2.5 font-medium text-gray-700 transition-all hover:bg-gray-200 dark:bg-gray-700 dark:text-white dark:hover:bg-gray-600">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col xl:flex-row gap-4 md:gap-6 2xl:gap-8 mt-4 items-stretch w-full">
        <!-- Hero Card: Total Armada -->
        <div class="w-full xl:w-4/12 2xl:w-3/12 relative rounded-3xl border border-gray-200 bg-white p-6 lg:p-8 xl:p-6 2xl:p-8 dark:border-gray-800 dark:bg-gray-900 overflow-hidden shadow-sm flex flex-col justify-between min-h-[280px]">
            <!-- Background Decoration -->
            <div class="absolute -right-10 -top-10 h-40 w-40 rounded-full bg-primary/10 blur-3xl"></div>
            <div class="absolute -bottom-10 -left-10 h-40 w-40 rounded-full bg-success-500/10 blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <p class="text-[11px] 2xl:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse shadow-[0_0_8px_rgba(var(--color-primary),0.5)]"></span>
                        Status Armada Real-time
                    </p>
                    <button @click="openModal('total')" class="p-2 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:hover:bg-gray-700 rounded-xl text-gray-400 transition-colors shadow-sm border border-gray-100 dark:border-gray-700">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    </button>
                </div>
                <div class="flex items-baseline gap-3 mb-8">
                    <h4 class="text-5xl lg:text-7xl xl:text-5xl 2xl:text-7xl font-black text-gray-900 dark:text-white tracking-tight leading-none">{{ $totalKendaraan }}</h4>
                    <span class="text-sm font-bold text-gray-400 uppercase tracking-widest">Unit</span>
                </div>
            </div>

            <!-- Visual Bar -->
            <div class="relative z-10 mt-auto">
                <div class="flex flex-wrap items-center justify-between text-[10px] 2xl:text-xs font-bold uppercase tracking-wider mb-4 gap-2 xl:gap-3">
                    <span class="flex items-center gap-1.5 xl:gap-2 text-success-600 dark:text-success-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-success-500"></div> Tersedia ({{ $siapDipakai }})
                    </span>
                    <span class="flex items-center gap-1.5 xl:gap-2 text-warning-600 dark:text-warning-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-warning-500"></div> Dipakai ({{ $kendaraanAktif }})
                    </span>
                    <span class="flex items-center gap-1.5 xl:gap-2 text-error-600 dark:text-error-400">
                        <div class="w-2.5 h-2.5 rounded-full bg-error-500"></div> Perbaikan ({{ $kendaraanPerbaikan }})
                    </span>
                </div>
                <div class="h-3 2xl:h-4 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden flex gap-1 shadow-inner">
                    <div class="h-full bg-success-500 transition-all duration-1000 relative" style="width: {{ $totalKendaraan > 0 ? ($siapDipakai / $totalKendaraan) * 100 : 0 }}%">
                        <div class="absolute inset-0 bg-white/20 w-full" style="background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;"></div>
                    </div>
                    <div class="h-full bg-warning-500 transition-all duration-1000" style="width: {{ $totalKendaraan > 0 ? ($kendaraanAktif / $totalKendaraan) * 100 : 0 }}%"></div>
                    <div class="h-full bg-error-500 transition-all duration-1000" style="width: {{ $totalKendaraan > 0 ? ($kendaraanPerbaikan / $totalKendaraan) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        <!-- 3 Secondary Cards -->
        <div class="w-full xl:w-8/12 2xl:w-9/12 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 md:gap-6">
            
            <!-- Aktif/Utilitas -->
            <div class="relative rounded-3xl border border-gray-200 bg-white p-6 xl:p-5 2xl:p-6 dark:border-gray-800 dark:bg-gray-900 shadow-sm flex flex-col justify-between group hover:border-success-500/50 transition-colors min-h-[280px]">
                <button @click="openModal('aktif')" class="absolute right-5 top-5 text-gray-300 hover:text-success-500 transition-colors bg-gray-50 hover:bg-success-50 dark:bg-gray-800 dark:hover:bg-success-900/30 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </button>
                <div>
                    <div class="flex items-center justify-center w-10 h-10 2xl:w-12 2xl:h-12 mb-5 bg-success-50 rounded-2xl dark:bg-success-500/10 text-success-600 dark:text-success-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                        </svg>
                    </div>
                    <p class="text-[10px] 2xl:text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Tingkat Utilitas</p>
                    <div class="flex items-baseline gap-1.5">
                        <h4 class="text-4xl xl:text-3xl 2xl:text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $utilizationRate }}<span class="text-xl 2xl:text-2xl font-bold opacity-70">%</span></h4>
                    </div>
                </div>
                <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs 2xl:text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 2xl:w-2 2xl:h-2 rounded-full bg-success-500 shadow-[0_0_6px_rgba(16,185,129,0.4)]"></span>
                        <span class="font-bold text-gray-900 dark:text-gray-200">{{ $kendaraanAktif }}</span> Kendaraan Aktif
                    </p>
                </div>
            </div>

            <!-- Tugas Aktif -->
            <div class="relative rounded-3xl border border-gray-200 bg-white p-6 xl:p-5 2xl:p-6 dark:border-gray-800 dark:bg-gray-900 shadow-sm flex flex-col justify-between group hover:border-warning-500/50 transition-colors min-h-[280px]">
                <button @click="openModal('tugas')" class="absolute right-5 top-5 text-gray-300 hover:text-warning-500 transition-colors bg-gray-50 hover:bg-warning-50 dark:bg-gray-800 dark:hover:bg-warning-900/30 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </button>
                <div>
                    <div class="flex items-center justify-center w-10 h-10 2xl:w-12 2xl:h-12 mb-5 bg-warning-50 rounded-2xl dark:bg-warning-500/10 text-warning-600 dark:text-warning-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <path d="M9 12h6"></path><path d="M9 16h6"></path><path d="M9 8h6"></path>
                        </svg>
                    </div>
                    <p class="text-[10px] 2xl:text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Penugasan Aktif</p>
                    <div class="flex items-baseline gap-1.5">
                        <h4 class="text-4xl xl:text-3xl 2xl:text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $penugasanAktif }}</h4>
                    </div>
                </div>
                <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs 2xl:text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 2xl:w-2 2xl:h-2 rounded-full bg-warning-500 shadow-[0_0_6px_rgba(245,158,11,0.4)]"></span>
                        <span class="font-bold text-gray-900 dark:text-gray-200">{{ $assignmentsToday }}</span> Mulai Hari Ini
                    </p>
                </div>
            </div>

            <!-- Perbaikan Aktif -->
            <div class="relative rounded-3xl border border-gray-200 bg-white p-6 xl:p-5 2xl:p-6 dark:border-gray-800 dark:bg-gray-900 shadow-sm flex flex-col justify-between group hover:border-error-500/50 transition-colors min-h-[280px]">
                <button @click="openModal('perbaikan')" class="absolute right-5 top-5 text-gray-300 hover:text-error-500 transition-colors bg-gray-50 hover:bg-error-50 dark:bg-gray-800 dark:hover:bg-error-900/30 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                </button>
                <div>
                    <div class="flex items-center justify-center w-10 h-10 2xl:w-12 2xl:h-12 mb-5 bg-error-50 rounded-2xl dark:bg-error-500/10 text-error-600 dark:text-error-400">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <p class="text-[10px] 2xl:text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-2">Maintenance</p>
                    <div class="flex items-baseline gap-1.5">
                        <h4 class="text-4xl xl:text-3xl 2xl:text-4xl font-black text-gray-900 dark:text-white leading-none">{{ $kendaraanPerbaikan }}</h4>
                    </div>
                </div>
                <div class="mt-6 pt-5 border-t border-gray-100 dark:border-gray-800">
                    <p class="text-xs 2xl:text-sm font-medium text-gray-600 dark:text-gray-400 flex items-center gap-2">
                        <span class="w-1.5 h-1.5 2xl:w-2 2xl:h-2 rounded-full bg-error-500 shadow-[0_0_6px_rgba(239,68,68,0.4)]"></span>
                        Avg <span class="font-bold text-gray-900 dark:text-gray-200">{{ $avgRepairDuration }} Hari</span>
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
