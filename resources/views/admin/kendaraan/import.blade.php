@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">
    <!-- Breadcrumb Start -->
    <div class="flex flex-col gap-2 mb-6 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
                Import Data Kendaraan
            </h2>
            <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                Upload file Excel untuk menambahkan atau memperbarui data kendaraan pada sistem.
            </p>
        </div>

        <nav>
            <ol class="flex items-center gap-2 text-sm">
                <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
                <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('kendaraan.index') }}">Kendaraan /</a></li>
                <li class="font-medium text-black-500 hover:text-primary transition-colors dark:text-white">Import</li>
            </ol>
        </nav>
    </div>
    <!-- Breadcrumb End -->

    <div class="grid grid-cols-1 gap-6">
        <div class="flex flex-col gap-6">
            <!-- Form Card -->
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="mb-6 flex items-center justify-between border-b border-gray-100 pb-4 dark:border-gray-800/50">
                    <h3 class="flex items-center gap-2.5 font-semibold text-gray-800 dark:text-white/90">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                <polyline points="17 8 12 3 7 8"></polyline>
                                <line x1="12" y1="3" x2="12" y2="15"></line>
                            </svg>
                        </div>
                        Pilih Dokumen Excel
                    </h3>

                    <a href="{{ route('kendaraan.import.template') }}" class="flex items-center gap-2 rounded-lg bg-emerald-500/10 px-4 py-2 text-sm font-semibold text-emerald-600 transition-all hover:bg-emerald-500 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="7 10 12 15 17 10"></polyline>
                            <line x1="12" y1="15" x2="12" y2="3"></line>
                        </svg>
                        Unduh Template Excel
                    </a>
                </div>

                <div class="space-y-6">
                    @if(session('success'))
                        <div class="flex w-full items-start gap-4 rounded-xl border border-emerald-100 bg-emerald-50/50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600 dark:bg-emerald-500/20 dark:text-emerald-400">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12"></polyline>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-emerald-900 dark:text-emerald-400">Berhasil!</h5>
                                <p class="text-sm text-emerald-800/80 dark:text-emerald-400/80">{{ session('success') }}</p>
                            </div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="flex w-full items-start gap-4 rounded-xl border border-red-100 bg-red-50/50 p-4 dark:border-red-500/20 dark:bg-red-500/5">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </div>
                            <div>
                                <h5 class="font-bold text-red-900 dark:text-red-400">Terjadi Kesalahan</h5>
                                <p class="text-sm text-red-800/80 dark:text-red-400/80">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('kendaraan.import.post') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="mb-2.5 block font-medium text-gray-700 dark:text-gray-300">
                                File Excel <span class="text-red-500">*</span>
                            </label>
                            <div class="relative overflow-hidden rounded-xl border-2 border-dashed border-gray-200 bg-gray-50/50 p-8 transition-colors hover:border-primary/50 dark:border-gray-700 dark:bg-gray-800/20">
                                <input type="file" name="file" 
                                    class="absolute inset-0 z-10 h-full w-full cursor-pointer opacity-0" />
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="mb-4 rounded-full bg-white p-3 shadow-sm dark:bg-gray-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary dark:text-white">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="12" y1="18" x2="12" y2="12"></line>
                                            <line x1="9" y1="15" x2="15" y2="15"></line>
                                        </svg>
                                    </div>
                                    <p class="font-semibold text-gray-800 dark:text-white">Klik untuk upload atau drag and drop</p>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">XLSX atau XLS (Max. 10MB)</p>
                                </div>
                            </div>
                            @error('file')
                                <p class="mt-2 text-sm font-medium text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="rounded-xl border border-gray-100 bg-gray-50/50 p-5 dark:border-gray-800 dark:bg-white/[0.02]">
                            <h4 class="mb-4 flex items-center gap-2 text-sm font-bold text-gray-800 dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-primary">
                                    <circle cx="12" cy="12" r="10"></circle>
                                    <line x1="12" y1="16" x2="12" y2="12"></line>
                                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                                </svg>
                                Format Kolom Excel
                            </h4>
                            <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
                                <table class="w-full text-left text-xs">
                                    <thead class="bg-gray-100 dark:bg-gray-800">
                                        <tr>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">NO_POLISI</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">MERK</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">TIPE</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">NAMA_PADA_SIMAK</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">TAHUN</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">BBM</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">KATEGORI_KENDARAAN</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">JENIS_KENDARAAN</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">KM_TERAKHIR</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">STATUS_KENDARAAN</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">NAMA_PEMEGANG</th>
                                            <th class="py-2.5 px-3 font-semibold text-gray-700 dark:text-gray-300 uppercase whitespace-nowrap">KETERANGAN_PENGGUNAAN</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                        <tr class="text-gray-600 dark:text-gray-400">
                                            <td class="py-2.5 px-3">B 1234 ABC</td>
                                            <td class="py-2.5 px-3">TOYOTA</td>
                                            <td class="py-2.5 px-3">AVANZA</td>
                                            <td class="py-2.5 px-3">AVANZA 1.3 G M/T</td>
                                            <td class="py-2.5 px-3">2022</td>
                                            <td class="py-2.5 px-3">PERTALITE</td>
                                            <td class="py-2.5 px-3">R4</td>
                                            <td class="py-2.5 px-3">RANUM</td>
                                            <td class="py-2.5 px-3">15000</td>
                                            <td class="py-2.5 px-3">Tersedia</td>
                                            <td class="py-2.5 px-3">BRIPKA JOHN DOE</td>
                                            <td class="py-2.5 px-3">OPS</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.02]">
                                    <h5 class="mb-3 text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-primary"></div>
                                        Panduan Nilai Kolom
                                    </h5>
                                    <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                        <li><span class="font-semibold text-gray-800 dark:text-gray-200">Kategori:</span> R2, R4, R6</li>
                                        <li><span class="font-semibold text-gray-800 dark:text-gray-200">Jenis:</span> RANUM, RANSUS, LAINNYA</li>
                                        <li><span class="font-semibold text-gray-800 dark:text-gray-200">BBM:</span> PERTALITE, PERTAMAX, SOLAR, dll.</li>
                                        <li><span class="font-semibold text-gray-800 dark:text-gray-200">Status:</span> Tersedia, Perbaikan, Dipakai</li>
                                        <li><span class="font-semibold text-gray-800 dark:text-gray-200">KM Terakhir:</span> Isi angka (Jika kosong otomatis 0)</li>
                                    </ul>
                                </div>
                                <div class="rounded-xl border border-gray-100 bg-white p-4 dark:border-gray-800 dark:bg-white/[0.02]">
                                    <h5 class="mb-3 text-sm font-bold text-gray-800 dark:text-white flex items-center gap-2">
                                        <div class="h-1.5 w-1.5 rounded-full bg-amber-500"></div>
                                        Catatan Penting
                                    </h5>
                                    <ul class="space-y-2 text-xs text-gray-600 dark:text-gray-400">
                                        <li>* Kolom <span class="font-bold">NO_POLISI</span> bersifat unik & wajib diisi.</li>
                                        <li>* Jika <span class="font-bold">Nama Pemegang</span> atau <span class="font-bold">Keterangan</span> kosong, sistem otomatis mengisinya dengan "-".</li>
                                        <li>* Status kendaraan default akan diatur ke <span class="text-emerald-500 font-semibold">Tersedia</span> jika tidak ditentukan.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 pt-4 sm:flex-row">
                            <button type="submit" class="flex flex-1 items-center justify-center gap-2 rounded-xl bg-brand-500 px-10 py-3 text-sm font-medium text-white hover:bg-brand-600 shadow-theme-sm transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Mulai Proses Import
                            </button>
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-center rounded-xl border border-gray-200 px-8 py-3.5 font-semibold text-gray-700 transition-all hover:bg-gray-50 hover:border-gray-300 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-white/5 active:scale-[0.98]">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
