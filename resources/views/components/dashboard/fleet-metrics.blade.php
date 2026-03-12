@props([
    'totalKendaraan' => 0,
    'kendaraanAktif' => 0,
    'kendaraanPerbaikan' => 0,
    'penugasanAktif' => 0,
    'totalR2' => 0,
    'totalR4' => 0,
    'siapDipakai' => 0,
    'sedangTugas' => 0,
    'perbaikanTerbaru' => []
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
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Ringkasan Status Kendaraan</h3>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 rounded-2xl bg-success-50 dark:bg-success-500/10 col-span-2">
                            <p class="text-xs font-medium text-success-600 dark:text-success-400 uppercase tracking-wider">Kendaraan Aktif</p>
                            <p class="text-2xl font-bold text-success-600 dark:text-success-500">{{ $kendaraanAktif }} Unit</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roda 2</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalR2 }} Unit</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800">
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Roda 4</p>
                            <p class="text-xl font-bold text-gray-800 dark:text-white">{{ $totalR4 }} Unit</p>
                        </div>
                        <div class="p-5 rounded-2xl bg-primary/10 dark:bg-primary/20 col-span-2 flex justify-between items-center border border-primary/20">
                            <span class="font-bold text-primary italic underline-offset-4 underline uppercase tracking-widest text-sm">Total Kendaraan</span>
                            <span class="text-2xl font-black text-primary">{{ $totalKendaraan }} Unit</span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'aktif'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Status Kendaraan Operasional</h3>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="p-6 text-center rounded-2xl bg-success-50 dark:bg-success-500/10 border border-success-100 dark:border-success-500/20 mb-4">
                            <p class="text-sm font-semibold text-success-600 dark:text-success-400 uppercase tracking-widest">Total Kendaraan Aktif</p>
                            <p class="text-4xl font-black text-success-600 dark:text-success-500">{{ $kendaraanAktif }} Unit</p>
                        </div>
                        <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800 transition-all hover:border-success-200 dark:hover:border-success-500/30">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Kendaraan Siap Dipakai</span>
                            <span class="text-xl font-bold text-gray-800 dark:text-white">{{ $siapDipakai }} Unit</span>
                        </div>
                        <div class="flex justify-between items-center p-4 rounded-xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800 transition-all hover:border-warning-200 dark:hover:border-warning-500/30">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">Kendaraan Dalam Penugasan</span>
                            <span class="text-xl font-bold text-gray-800 dark:text-white">{{ $sedangTugas }} Unit</span>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'perbaikan'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Status Perbaikan Kendaraan</h3>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="p-4 text-center rounded-2xl bg-error-50 dark:bg-error-500/10 border border-error-100 dark:border-error-500/20">
                                <p class="text-xs font-semibold text-error-600 uppercase tracking-widest">Dalam Perbaikan</p>
                                <p class="text-3xl font-bold text-error-600">{{ $kendaraanPerbaikan }} Unit</p>
                            </div>
                            <div class="p-4 text-center rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-widest">Rata-rata Durasi</p>
                                <p class="text-xl font-bold text-gray-800 dark:text-white">Belum tersedia</p>
                            </div>
                        </div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest ml-1">3 Data Perbaikan Terbaru</p>
                        <div class="space-y-3">
                            @foreach($perbaikanTerbaru as $p)
                            <div class="p-4 rounded-2xl bg-gray-50/50 dark:bg-white/[0.02] border border-gray-100 dark:border-gray-800">
                                <div class="flex flex-col gap-1">
                                    <p class="text-sm font-bold text-gray-800 dark:text-white">
                                        {{ $p->kendaraan->no_polisi }} - {{ $p->kendaraan->merk }}
                                    </p>
                                    <div class="grid grid-cols-1 gap-1 text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                                        <p><span class="font-semibold text-error-600/70">Kerusakan:</span> {{ $p->keluhan }}</p>
                                        <p><span class="font-semibold">Sejak:</span> {{ \Carbon\Carbon::parse($p->tanggal_lapor)->translatedFormat('d F Y') }}</p>
                                        <p><span class="font-semibold">Durasi:</span> {{ (int) \Carbon\Carbon::parse($p->tanggal_lapor)->diffInDays() }} Hari</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="modalType === 'tugas'">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Status Penugasan Kendaraan</h3>
                        <button @click="showInfo = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                        </button>
                    </div>
                    <div class="space-y-6">
                        <div class="p-8 text-center rounded-3xl bg-warning-50 dark:bg-warning-500/10 border border-warning-100 dark:border-warning-500/20 shadow-inner">
                            <p class="text-sm font-bold text-warning-600 dark:text-warning-400 uppercase tracking-widest mb-1">Penugasan Aktif</p>
                            <p class="text-5xl font-black text-warning-600 dark:text-warning-500">{{ $penugasanAktif }} Unit</p>
                        </div>
                        <div class="flex justify-between items-center p-5 rounded-2xl bg-gray-50 dark:bg-white/5 border border-gray-100 dark:border-gray-800">
                            <span class="text-gray-600 dark:text-gray-400 font-semibold uppercase text-xs tracking-wider">Kendaraan Aktif</span>
                            <span class="text-2xl font-bold text-gray-800 dark:text-white">{{ $kendaraanAktif }} Unit</span>
                        </div>
                    </div>
                </div>
            </template>

            <div class="mt-8 flex justify-end">
                <button @click="showInfo = false" 
                        class="rounded-lg bg-primary px-6 py-2.5 font-medium text-white transition-all hover:bg-opacity-90">
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

        <div class="flex items-end justify-between mt-5">
            <div>
                <span class="text-sm text-gray-500 dark:text-gray-400">Total Kendaraan</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $totalKendaraan ?? 0 }}</h4>
            </div>
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
                <span class="text-sm text-gray-500 dark:text-gray-400">Kendaraan Aktif</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $kendaraanAktif ?? 0 }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-success-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-success-600 dark:bg-success-500/15 dark:text-success-500">
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
                <span class="text-sm text-gray-500 dark:text-gray-400">Kendaraan Perbaikan</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $kendaraanPerbaikan ?? 0 }}</h4>
            </div>
            <span class="flex items-center gap-1 rounded-full bg-error-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-error-600 dark:bg-error-500/15 dark:text-error-500">
                Bengkel
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
                <span class="text-sm text-gray-500 dark:text-gray-400">Penugasan Aktif</span>
                <h4 class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $penugasanAktif ?? 0 }}</h4>
            </div>
             <span class="flex items-center gap-1 rounded-full bg-warning-50 py-0.5 pl-2 pr-2.5 text-sm font-medium text-warning-600 dark:bg-warning-500/15 dark:text-warning-500">
                Dalam Tugas
            </span>
        </div>
    </div>
</div>
