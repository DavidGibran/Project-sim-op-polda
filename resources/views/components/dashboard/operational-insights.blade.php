@props(['komposisiKategori', 'komposisiJenis', 'distribusiBBM', 'statusArmada', 'totalKendaraan'])

<div class="h-full rounded-3xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-gray-900 md:p-6 flex flex-col">
    <div class="mb-8">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            Analisis Operasional
        </h3>
        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">
            Distribusi armada berdasarkan kategori dan bahan bakar
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10 flex-1">
        <!-- Section 1: Komposisi Kategori -->
        <div class="flex flex-col">
            <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-6">Komposisi Kategori</h4>
            
            <div class="space-y-6">
                @foreach($komposisiKategori as $label => $count)
                    @php 
                        $percentage = $totalKendaraan > 0 ? round(($count / $totalKendaraan) * 100) : 0;
                        // Map kategori to more readable labels if needed, or use as is
                        $displayLabel = match($label) {
                            'R2' => 'Roda 2 (Motor)',
                            'R4' => 'Roda 4 (Mobil)',
                            'R6' => 'Roda 6 (Truk/Bus)',
                            default => $label
                        };
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-brand-500 transition-colors">{{ $displayLabel }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $count }} Unit</span>
                                <span class="text-[10px] font-medium text-gray-400">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-brand-500 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section 2: Distribusi BBM -->
        <div class="flex flex-col">
            <h4 class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase tracking-[0.2em] mb-6">Penggunaan Bahan Bakar</h4>
            
            <div class="space-y-6">
                @php 
                    $totalBbm = array_sum($distribusiBBM);
                @endphp
                @foreach($distribusiBBM as $label => $count)
                    @php 
                        $percentage = $totalBbm > 0 ? round(($count / $totalBbm) * 100) : 0;
                    @endphp
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm font-semibold text-gray-700 dark:text-gray-300 group-hover:text-indigo-500 transition-colors">{{ $label }}</span>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $count }} Unit</span>
                                <span class="text-[10px] font-medium text-gray-400">{{ $percentage }}%</span>
                            </div>
                        </div>
                        <div class="h-1.5 w-full bg-gray-100 dark:bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-indigo-500 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach

                @if(empty($distribusiBBM))
                    <div class="flex flex-col items-center justify-center py-8 text-center">
                        <p class="text-sm text-gray-400">Data BBM belum tersedia</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
