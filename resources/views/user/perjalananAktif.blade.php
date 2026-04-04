@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

    {{-- 
        Header / Breadcrumb
        Dibuat konsisten dengan tampilan admin
    --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Perjalanan Aktif
            </h2>
        </div>

        <nav>
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a
                        class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400"
                        href="{{ route('kendaraan.dashboard') }}"
                    >
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-primary dark:text-white">
                    Perjalanan Aktif
                </li>
            </ol>
        </nav>
    </div>

    {{-- Alert message --}}
    @if(session('success'))
        <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-500/10 dark:text-green-400">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-500/10 dark:text-red-400">
            {{ session('error') }}
        </div>
    @endif

    {{-- 
        Jika tidak ada penugasan aktif
    --}}
    @if(!$penugasan)
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                Tidak ada perjalanan aktif saat ini.
            </p>
        </div>
    @else
        <div class="grid grid-cols-1 gap-6 xl:grid-cols-12">

            {{-- CARD DETAIL KENDARAAN --}}
            <div class="xl:col-span-8">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

                    {{-- Header Card --}}
                    <div class="mb-5">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Detail Kendaraan
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Informasi kendaraan dan penugasan aktif saat ini.
                        </p>
                    </div>

                    {{-- Detail informasi --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                        {{-- No Polisi --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                No. Polisi
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $kendaraan->no_polisi ?? '-' }}
                            </p>
                        </div>

                        {{-- Tipe Kendaraan --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tipe Kendaraan
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ trim(($kendaraan->merk ?? '-') . ' ' . ($kendaraan->tipe ?? '')) }}
                            </p>
                        </div>

                        {{-- Nama Pengemudi --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Nama Pengemudi
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $penugasan->pengemudi ?? '-' }}
                            </p>
                        </div>

                        {{-- KM Awal --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                KM Awal
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ number_format((int) ($penugasan->km_awal ?? $kendaraan->km_terakhir ?? 0), 0, ',', '.') }} km
                            </p>
                        </div>

                        {{-- Tujuan --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tujuan
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $penugasan->tujuan ?? '-' }}
                            </p>
                        </div>

                        {{-- Tanggal Tugas --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 dark:border-gray-800 dark:bg-gray-900/50">
                            <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tanggal Tugas
                            </p>
                            <p class="mt-1 text-base font-semibold text-gray-900 dark:text-white">
                                {{ $penugasan->tgl_tugas ? \Carbon\Carbon::parse($penugasan->tgl_tugas)->translatedFormat('d F Y') : '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Info kecil --}}
                    @if($penugasan->status === 'berjalan')
                        <div class="mt-5 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-500/10 dark:text-green-400">
                            Perjalanan sedang berjalan. Anda dapat melaporkan kendala atau menyelesaikan perjalanan.
                        </div>
                    @else
                        <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-500/10 dark:text-blue-400">
                            Menekan tombol <strong>Mulai Perjalanan</strong> akan memulai pencatatan waktu perjalanan.
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD AKSI --}}
            <div class="xl:col-span-4">
                <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">
                    <h3 class="mb-5 text-lg font-semibold text-gray-900 dark:text-white">
                        Aksi Perjalanan
                    </h3>

                    {{-- 
                        Jika status masih diterbitkan
                        maka pengemudi harus menerima tugas di sini atau di dashboard
                    --}}
                    @if($penugasan->status === 'diterbitkan')
                        <div class="space-y-3">
                            <div class="rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-sm text-blue-700 dark:border-blue-800 dark:bg-blue-500/10 dark:text-blue-400">
                                Penugasan baru telah diterbitkan untuk kendaraan ini.
                            </div>
                            
                            <form action="{{ route('kendaraan.penugasan.terima', $penugasan->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-primary px-5 py-3 text-sm font-medium text-white hover:opacity-90"
                                >
                                    Terima Tugas
                                </button>
                            </form>
                        </div>

                    {{-- 
                        Jika status diterima
                        munculkan tombol mulai perjalanan
                    --}}
                    @elseif($penugasan->status === 'diterima')
                        <div class="space-y-3">
                            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-500/10 dark:text-green-400">
                                Tugas telah diterima. Silakan mulai perjalanan jika sudah siap.
                            </div>
                            
                            <form action="{{ route('kendaraan.penugasan.mulai', $penugasan->id) }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-xl bg-success-600 px-5 py-3 text-sm font-medium text-white hover:opacity-90"
                                >
                                    Mulai Perjalanan
                                </button>
                            </form>
                        </div>

                    {{-- 
                        Jika status berjalan
                        tampilkan tombol laporkan kendala + selesaikan perjalanan
                    --}}
                    @elseif($penugasan->status === 'berjalan')
                        <div class="space-y-3">
                            {{-- Tombol laporkan kendala --}}
                            <a
                                href="https://wa.me/"
                                target="_blank"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-warning-500 px-5 py-3 text-sm font-medium text-white hover:opacity-90"
                            >
                                Laporkan Kendala
                            </a>

                            {{-- Tombol selesai perjalanan --}}
                            <button
                                type="button"
                                onclick="openSelesaiModal()"
                                class="inline-flex w-full items-center justify-center rounded-xl bg-success-600 px-5 py-3 text-sm font-medium text-white hover:opacity-90"
                            >
                                Selesaikan Perjalanan
                            </button>
                        </div>

                    {{-- 
                        Kondisi fallback
                    --}}
                    @else
                        <div class="rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600 dark:border-gray-800 dark:bg-gray-900/50 dark:text-gray-400">
                            Tidak ada aksi yang tersedia untuk status ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>

{{-- 
    Modal Selesaikan Perjalanan
    Akan dipakai saat pengemudi menekan tombol "Selesaikan Perjalanan"
--}}
@if($penugasan && $penugasan->status === 'berjalan')
<div id="selesaiModal" class="fixed inset-0 z-999999 hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Selesaikan Perjalanan
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Isi formulir berikut untuk menyelesaikan perjalanan anda.
                </p>
            </div>

            <button
                type="button"
                onclick="closeSelesaiModal()"
                class="text-gray-500 hover:text-black dark:hover:text-white"
            >
                ✕
            </button>
        </div>

        <form
            action="{{ route('kendaraan.penugasan.selesai', $penugasan->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="space-y-4">
                {{-- KM awal referensi --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        KM Awal
                    </label>
                    <div class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-2 text-sm text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-300">
                        {{ number_format((int) ($penugasan->km_awal ?? $kendaraan->km_terakhir ?? 0), 0, ',', '.') }} km
                    </div>
                </div>

                {{-- KM akhir --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        KM Terakhir <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        name="km_akhir"
                        placeholder="Masukkan KM Terakhir.."
                        required
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                </div>

                {{-- Foto odometer --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Foto Odometer (Opsional)
                    </label>
                    <input
                        type="file"
                        name="foto_odometer"
                        accept="image/*"
                        capture="environment"
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Ambil foto odometer kendaraan dengan jelas.
                    </p>
                </div>

                {{-- Catatan --}}
                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Catatan (Opsional)
                    </label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        rows="4"
                        maxlength="255"
                        placeholder="Masukkan catatan tambahan..."
                        class="w-full rounded-lg border border-gray-200 bg-transparent px-4 py-2 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        oninput="updateCharCount()"
                    ></textarea>
                    <div class="mt-1 text-right text-xs text-gray-500 dark:text-gray-400">
                        <span id="charCount">0</span>/255
                    </div>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Tanda <span class="text-red-500">*</span> mewakili form yang wajib diisi.
                </p>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeSelesaiModal()"
                    class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-800 dark:text-gray-300 dark:hover:bg-white/5"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="rounded-lg bg-primary px-4 py-2 text-sm font-medium text-white hover:opacity-90"
                >
                    Konfirmasi
                </button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    /**
     * Membuka modal selesai perjalanan
     */
    function openSelesaiModal() {
        const modal = document.getElementById('selesaiModal');
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /**
     * Menutup modal selesai perjalanan
     */
    function closeSelesaiModal() {
        const modal = document.getElementById('selesaiModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /**
     * Menghitung jumlah karakter textarea catatan
     */
    function updateCharCount() {
        const textarea = document.getElementById('catatan');
        const counter = document.getElementById('charCount');

        if (textarea && counter) {
            counter.textContent = textarea.value.length;
        }
    }

    /**
     * Tutup modal saat tombol Escape ditekan
     */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeSelesaiModal();
        }
    });
</script>
@endsection