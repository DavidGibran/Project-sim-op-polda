@extends('layouts.app')

@section('content')
<!-- Page Header -->
<div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white/90">
            Tambah Kendaraan Baru
        </h2>
    </div>
    
    <nav>
        <ol class="flex items-center gap-2 text-sm">
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('admin.dashboard') }}">Dashboard /</a></li>
            <li><a class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400" href="{{ route('kendaraan.index') }}">Master Kendaraan /</a></li>
            <li class="font-medium text-primary dark:text-white">Tambah</li>
        </ol>
    </nav>
</div>

<!-- Form Card -->
<div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
    <form action="{{ route('kendaraan.store') }}" method="POST">
        @csrf
        <div class="p-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- No Polisi -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">No. Polisi <span class="text-error">*</span></label>
                    <input type="text" name="no_polisi" value="{{ old('no_polisi') }}" required placeholder="Misal: L 1234 AB" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('no_polisi') border-error @enderror">
                    @error('no_polisi') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Merk -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Merk <span class="text-error">*</span></label>
                    <input type="text" name="merk" value="{{ old('merk') }}" required placeholder="Misal: Honda" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('merk') border-error @enderror">
                    @error('merk') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Tipe -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Tipe <span class="text-error">*</span></label>
                    <input type="text" name="tipe" value="{{ old('tipe') }}" required placeholder="Misal: CR-V" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('tipe') border-error @enderror">
                    @error('tipe') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Tahun -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Tahun <span class="text-error">*</span></label>
                    <input type="number" name="tahun" value="{{ old('tahun') }}" required min="1900" max="{{ date('Y') + 1 }}" placeholder="Misal: 2021" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('tahun') border-error @enderror">
                    @error('tahun') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Kategori Kendaraan -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Kategori Kendaraan <span class="text-error">*</span></label>
                    <select name="kategori_kendaraan" required class="w-full rounded-lg border border-gray-200 bg-white py-2 px-4 text-gray-700 outline-none focus:border-primary focus-visible:shadow-none dark:border-primary dark:bg-gray-900 dark:text-gray-200 @error('kategori_kendaraan') border-error @enderror">
                        <option value="" class="text-gray-700 dark:text-white">Pilih Kategori</option>
                        <option value="R2" class="text-gray-700 dark:text-white" {{ old('kategori_kendaraan') == 'R2' ? 'selected' : '' }}>Roda 2 (R2)</option>
                        <option value="R4" class="text-gray-700 dark:text-white" {{ old('kategori_kendaraan') == 'R4' ? 'selected' : '' }}>Roda 4 (R4)</option>
                        <option value="R6" class="text-gray-700 dark:text-white" {{ old('kategori_kendaraan') == 'R6' ? 'selected' : '' }}>Roda 6 (R6)</option>
                        <option value="Lainnya" class="text-gray-700 dark:text-white" {{ old('kategori_kendaraan') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('kategori_kendaraan') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Jenis Kendaraan -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Jenis Kendaraan <span class="text-error">*</span></label>
                    <select name="jenis_kendaraan" required class="w-full rounded-lg border border-gray-200 bg-white py-2 px-4 text-gray-700 outline-none focus:border-primary focus-visible:shadow-none dark:border-strokedark dark:bg-gray-900 dark:text-gray-200 @error('jenis_kendaraan') border-error @enderror">
                        <option value="" class="text-gray-700 dark:text-white">Pilih Jenis</option>
                        <option value="RANUM" class="text-gray-700 dark:text-white" {{ old('jenis_kendaraan') == 'RANUM' ? 'selected' : '' }}>RANUM</option>
                        <option value="RANSUS" class="text-gray-700 dark:text-white" {{ old('jenis_kendaraan') == 'RANSUS' ? 'selected' : '' }}>RANSUS</option>
                        <option value="OPERASIONAL" class="text-gray-700 dark:text-white" {{ old('jenis_kendaraan') == 'OPERASIONAL' ? 'selected' : '' }}>OPERASIONAL</option>
                        <option value="LAINNYA" class="text-gray-700 dark:text-white" {{ old('jenis_kendaraan') == 'LAINNYA' ? 'selected' : '' }}>LAINNYA</option>
                    </select>
                    @error('jenis_kendaraan') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- KM Terakhir -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">KM Terakhir <span class="text-error">*</span></label>
                    <input type="number" name="km_terakhir" value="{{ old('km_terakhir', 0) }}" required min="0" placeholder="Misal: 12000" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('km_terakhir') border-error @enderror">
                    @error('km_terakhir') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <!-- Status -->
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Status Kendaraan <span class="text-error">*</span></label>
                    <select name="status" required class="w-full rounded-lg border border-gray-200 bg-white py-2 px-4 text-gray-700 outline-none focus:border-primary focus-visible:shadow-none dark:border-strokedark dark:bg-gray-900 dark:text-gray-200 @error('status') border-error @enderror">
                        <option value="Tersedia" class="text-gray-700 dark:text-white" {{ old('status', 'Tersedia') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Dipakai" class="text-gray-700 dark:text-white" {{ old('status') == 'Dipakai' ? 'selected' : '' }}>Dipakai</option>
                        <option value="Perbaikan" class="text-gray-700 dark:text-white" {{ old('status') == 'Perbaikan' ? 'selected' : '' }}>Perbaikan</option>
                    </select>
                    @error('status') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Keterangan -->
            <div class="mt-6">
                <label class="mb-2 block text-sm font-medium text-gray-800 dark:text-white/90">Keterangan Penggunaan</label>
                <textarea name="keterangan_penggunaan" rows="3" class="w-full rounded-lg border border-gray-200 bg-transparent py-2 px-4 outline-none focus:border-primary focus-visible:shadow-none dark:border-gray-800 dark:bg-gray-900/50 dark:text-white @error('keterangan_penggunaan') border-error @enderror" placeholder="Deskripsi pemakaian atau keadaan (opsional)">{{ old('keterangan_penggunaan') }}</textarea>
                @error('keterangan_penggunaan') <span class="text-xs text-error mt-1 block">{{ $message }}</span> @enderror
            </div>
        </div>
        
        <div class="border-t border-gray-100 dark:border-gray-800 px-6 py-5 flex items-center justify-end gap-3 bg-gray-50 dark:bg-white/5 rounded-b-2xl">
            <a href="{{ route('kendaraan.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-5 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-100 dark:border-gray-700 dark:text-gray-300 dark:hover:bg-gray-800 transition-colors">
                Batal
            </a>
            <button type="submit" class="inline-flex items-center justify-center rounded-lg bg-primary px-5 py-2.5 text-center text-sm font-medium text-gray-900 hover:bg-primary/90 dark:bg-primary dark:text-white transition-all">
                Simpan Kendaraan
            </button>
        </div>
    </form>
</div>
@endsection
