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
    // New Props
    'avgOdometer' => 0,
    'oldestVehicle' => null,
    'newestVehicle' => null,
    'utilizationRate' => 0,
    'topVehicles' => [],
    'avgRepairDuration' => 0,
    'oldestRepair' => null,
    'assignmentsToday' => 0,
    'assignmentsFinishedToday' => 0,
    'topDestination' => null,
])

<div x-data="{ 
    showInfo: false, 
    modalType: '',
    openModal(type) {
        this.modalType = type;
        this.showInfo = true;
    }
}" class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4 md:gap-6">
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
             class="w-full max-w-[500px] rounded-2xl bg-white p-8 shadow-2xl dark:bg-gray-900">
            
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
                                <p class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mb-1">Rentang Armada</p>
                                <p class="text-xl font-black text-gray-800 dark:text-white">{{ $oldestVehicle }} - {{ $newestVehicle }}</p>
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
                                        <div class="w-8 h-8 rounded-lg bg-white dark:bg-gray-800 flex items-center justify-center text-[10px] font-bold shadow-sm border border-gray-100 dark:border-gray-700">
                                            {{ $v->kategori_kendaraan }}
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
                                <p class="text-xs text-gray-700 dark:text-gray-300 mt-0.5">
                                    <span class="font-bold">{{ $oldestRepair->kendaraan->no_polisi }}</span> telah diperbaiki selama {{ \Carbon\Carbon::parse($oldestRepair->tanggal_lapor)->diffInDays() }} hari.
                                </p>
                            </div>
                        </div>
                        @endif

                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Update Perbaikan Terkini</p>
                            <div class="space-y-3">
                                @forelse($perbaikanTerbaru as $p)
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
                                                {{ (int) \Carbon\Carbon::parse($p->tanggal_lapor)->diffInDays() }} Hari
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

                        @if($topDestination)
                        <div class="p-4 rounded-xl bg-primary/5 border border-primary/10 flex items-center justify-between">
                            <div>
                                <p class="text-[10px] font-bold text-primary uppercase tracking-widest mb-1">Destinasi Terpopuler</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white line-clamp-1">{{ $topDestination->tujuan }}</p>
                            </div>
                            <div class="bg-white dark:bg-gray-800 px-3 py-1 rounded-full border border-primary/20 text-xs font-black text-primary">
                                {{ $topDestination->total }} <span class="text-[10px] font-normal opacity-70">Unit</span>
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

    <!-- Card 1: Total Kendaraan -->
    <div class="relative rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <button @click="openModal('total')" 
                class="absolute right-4 top-4 text-gray-400 hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </button>
        
        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 text-gray-800 dark:text-white/90">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.7 10.6 16 10 16 10s-1.3-1.4-2.2-2.3c-.5-.4-1.1-.7-1.8-.7H5c-.6 0-1.1.4-1.4.9l-1.4 2.9c-.1.3-.2.6-.2.9V16c0 .6.4 1 1 1h2"></path>
                <circle cx="7" cy="17" r="2"></circle>
                <circle cx="17" cy="17" r="2"></circle>
            </svg>
        </div>

        <div class="mt-5">
            <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Total Kendaraan</p>
            <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $totalKendaraan ?? 0 }}</h4>
        </div>
    </div>

    <!-- Card 2: Kendaraan Aktif -->
    <div class="relative rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <button @click="openModal('aktif')" 
                class="absolute right-4 top-4 text-gray-400 hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </button>

        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 text-success-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 16H9m10 0h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-1a2 2 0 0 1 2-2zM5 16h1a2 2 0 0 1 2 2v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-1a2 2 0 0 1 2-2z"></path>
                <path d="M4.5 16.5 4 11h16l-.5 5.5M6 11l.5-4h11l.5 4"></path>
                <path d="M12 5V2"></path>
            </svg>
        </div>

        <div class="flex items-end justify-between mt-5">
            <div>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kendaraan Aktif</p>
                <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $kendaraanAktif ?? 0 }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-xs font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
                <span class="w-2 h-2 rounded-full bg-success-600 animate-pulse"></span>
                Beroperasi
            </span>
        </div>
    </div>

    <!-- Card 3: Kendaraan Perbaikan -->
    <div class="relative rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <button @click="openModal('perbaikan')" 
                class="absolute right-4 top-4 text-gray-400 hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </button>

        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 text-error-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>

        <div class="flex items-end justify-between mt-5">
            <div>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Kendaraan Perbaikan</p>
                <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $kendaraanPerbaikan ?? 0 }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-error-50 py-0.5 pl-2 pr-2.5 text-xs font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                Diperbaiki
            </span>
        </div>
    </div>

    <!-- Card 4: Penugasan Aktif -->
    <div class="relative rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
        <button @click="openModal('tugas')" 
                class="absolute right-4 top-4 text-gray-400 hover:text-primary transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
        </button>

        <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-xl dark:bg-gray-800 text-warning-600">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <path d="M9 12h6"></path>
                <path d="M9 16h6"></path>
                <path d="M9 8h6"></path>
            </svg>
        </div>

        <div class="flex items-end justify-between mt-5">
            <div>
                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400">Penugasan Aktif</p>
                <h4 class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ $penugasanAktif ?? 0 }}</h4>
            </div>
             <span class="flex items-center gap-1 rounded-full bg-warning-50 py-0.5 pl-2 pr-2.5 text-xs font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                Bertugas
            </span>
        </div>
    </div>
</div>
