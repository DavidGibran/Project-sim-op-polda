@extends('layouts.app')

@section('content')
<div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6">

    {{-- 
        Header / Breadcrumb
    --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-title-md2 font-bold text-black dark:text-white">
                Log Pemakaian
            </h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Riwayat perjalanan kendaraan yang telah diselesaikan oleh pengemudi.
            </p>
        </div>

        <nav>
            <ol class="flex items-center gap-2 text-sm">
                <li>
                    <a
                        class="font-medium text-gray-500 hover:text-primary transition-colors dark:text-gray-400"
                        href="{{ route('admin.dashboard') }}"
                    >
                        Dashboard /
                    </a>
                </li>
                <li class="font-medium text-primary dark:text-white">
                    Log Pemakaian
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
        Card utama log pemakaian
    --}}
    <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-800 dark:bg-white/[0.03]">

        {{-- Header card + filter --}}
        <div class="border-b border-gray-100 px-5 py-4 dark:border-gray-800 sm:px-6">
            <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Data Log Pemakaian
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Menampilkan semua record perjalanan yang telah selesai.
                    </p>
                </div>

                {{-- Form filter --}}
                <form action="{{ route('log.index') }}" method="GET" class="flex flex-wrap items-center gap-3">

                    {{-- Search --}}
                    <div class="relative">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search ?? '' }}"
                            placeholder="Cari kode tugas, pengemudi, tujuan..."
                            class="w-full sm:w-72 rounded-lg border border-gray-200 bg-transparent py-2 pl-10 pr-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        >
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </span>
                    </div>

                    {{-- Tanggal dari --}}
                    <input
                        type="date"
                        name="tanggal_dari"
                        value="{{ $tanggalDari ?? '' }}"
                        class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        title="Tanggal Dari"
                    >

                    {{-- Tanggal sampai --}}
                    <input
                        type="date"
                        name="tanggal_sampai"
                        value="{{ $tanggalSampai ?? '' }}"
                        class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                        title="Tanggal Sampai"
                    >

                    {{-- Tombol filter --}}
                    <button
                        type="submit"
                        class="inline-flex h-9 items-center justify-center rounded-lg bg-gray-100 px-4 text-sm font-medium text-gray-700 hover:bg-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 transition-all"
                    >
                        Filter
                    </button>

                    {{-- Per page --}}
                    <select
                        name="per_page"
                        onchange="this.form.submit()"
                        class="rounded-lg border border-gray-200 bg-transparent py-2 px-4 text-sm outline-none focus:border-primary dark:border-gray-800 dark:bg-gray-900 dark:text-white"
                    >
                        @foreach([10, 25, 50, 100] as $val)
                            <option value="{{ $val }}" {{ (int)($perPage ?? 10) === $val ? 'selected' : '' }}>
                                {{ $val }} data
                            </option>
                        @endforeach
                    </select>

                    {{-- Reset --}}
                    @if(($search ?? null) || ($tanggalDari ?? null) || ($tanggalSampai ?? null))
                        <a
                            href="{{ route('log.index') }}"
                            class="text-sm text-gray-500 hover:text-primary dark:text-gray-400"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>
        </div>

        {{-- Table ringkas --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-gray-50 dark:bg-white/5">
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Kode Tugas
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Pengemudi
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Tipe Kendaraan
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            Tujuan
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            KM Awal
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                            KM Akhir
                        </th>
                        <th class="px-5 py-4 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 text-center">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                            <td class="px-5 py-4">
                                <p class="text-sm font-bold text-gray-900 dark:text-white">
                                    {{ $log->kode_tugas ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $log->nopol ?? '-' }}
                                </p>
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $log->nama_pengemudi ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $log->tipe_kendaraan ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $log->tujuan ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ number_format((int) ($log->km_awal ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                                {{ number_format((int) ($log->km_akhir ?? 0), 0, ',', '.') }}
                            </td>

                            <td class="px-5 py-4 text-center">
                                @php
                                    $detailData = [
                                        'kode_tugas' => $log->kode_tugas,
                                        'tanggal_tugas' => $log->tanggal_tugas
                                            ? \Carbon\Carbon::parse($log->tanggal_tugas)->translatedFormat('d F Y')
                                            : '-',
                                        'nama_pengemudi' => $log->nama_pengemudi,
                                        'nopol' => $log->nopol,
                                        'jenis_kendaraan' => $log->jenis_kendaraan,
                                        'tipe_kendaraan' => $log->tipe_kendaraan,
                                        'tujuan' => $log->tujuan,
                                        'km_awal' => number_format((int) ($log->km_awal ?? 0), 0, ',', '.'),
                                        'km_akhir' => number_format((int) ($log->km_akhir ?? 0), 0, ',', '.'),
                                        'foto_odometer' => $log->foto_odometer ? asset('storage/' . $log->foto_odometer) : null,
                                        'waktu_mulai' => $log->waktu_mulai
                                            ? \Carbon\Carbon::parse($log->waktu_mulai)->format('d-m-Y H:i')
                                            : '-',
                                        'waktu_selesai' => $log->waktu_selesai
                                            ? \Carbon\Carbon::parse($log->waktu_selesai)->format('d-m-Y H:i')
                                            : '-',
                                        'catatan' => $log->catatan ?: '-',
                                    ];
                                @endphp

                                <button
                                    type="button"
                                    data-detail='@json($detailData)'
                                    onclick="openDetailModal(this)"
                                    class="flex h-8 w-8 mx-auto items-center justify-center rounded-lg bg-gray-100 text-gray-500 hover:bg-success-500 hover:text-white transition-all dark:bg-gray-800 dark:text-gray-400"
                                    title="Detail"
                                >
                                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        <circle cx="12" cy="12" r="3" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada data log pemakaian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-800">
            {{ $logs->links() }}
        </div>
    </div>
</div>

{{-- Modal Detail --}}
<div id="detailModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/50 px-4">
    <div class="w-full max-w-3xl rounded-2xl bg-white p-6 shadow-xl dark:bg-gray-900">
        <div class="mb-4 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                Detail Pemakaian
            </h3>

            <button
                type="button"
                onclick="closeDetailModal()"
                class="text-gray-500 hover:text-black dark:hover:text-white"
            >
                ✕
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 text-sm">
            <div>
                <p class="text-gray-500 dark:text-gray-400">Kode Tugas</p>
                <p id="detail_kode_tugas" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Tanggal Tugas</p>
                <p id="detail_tanggal_tugas" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Pengemudi</p>
                <p id="detail_nama_pengemudi" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Nopol</p>
                <p id="detail_nopol" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Jenis Kendaraan</p>
                <p id="detail_jenis_kendaraan" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Tipe Kendaraan</p>
                <p id="detail_tipe_kendaraan" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Tujuan</p>
                <p id="detail_tujuan" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">KM Awal</p>
                <p id="detail_km_awal" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">KM Akhir</p>
                <p id="detail_km_akhir" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Perjalanan Dimulai</p>
                <p id="detail_waktu_mulai" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div>
                <p class="text-gray-500 dark:text-gray-400">Perjalanan Selesai</p>
                <p id="detail_waktu_selesai" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 dark:text-gray-400">Catatan</p>
                <p id="detail_catatan" class="font-medium text-gray-900 dark:text-white">-</p>
            </div>

            <div class="md:col-span-2">
                <p class="text-gray-500 dark:text-gray-400 mb-2">Foto Odometer</p>
                <img id="detail_foto_odometer" src="" alt="Foto Odometer" class="hidden max-h-80 rounded-xl border border-gray-200 dark:border-gray-800">
                <p id="detail_foto_placeholder" class="text-sm text-gray-500 dark:text-gray-400">Tidak ada foto.</p>
            </div>
        </div>
    </div>
</div>

<script>
    /**
     * Buka modal detail
     */
    function openDetailModal(button) {
        const data = JSON.parse(button.dataset.detail);

        document.getElementById('detail_kode_tugas').textContent = data.kode_tugas ?? '-';
        document.getElementById('detail_tanggal_tugas').textContent = data.tanggal_tugas ?? '-';
        document.getElementById('detail_nama_pengemudi').textContent = data.nama_pengemudi ?? '-';
        document.getElementById('detail_nopol').textContent = data.nopol ?? '-';
        document.getElementById('detail_jenis_kendaraan').textContent = data.jenis_kendaraan ?? '-';
        document.getElementById('detail_tipe_kendaraan').textContent = data.tipe_kendaraan ?? '-';
        document.getElementById('detail_tujuan').textContent = data.tujuan ?? '-';
        document.getElementById('detail_km_awal').textContent = data.km_awal ?? '-';
        document.getElementById('detail_km_akhir').textContent = data.km_akhir ?? '-';
        document.getElementById('detail_waktu_mulai').textContent = data.waktu_mulai ?? '-';
        document.getElementById('detail_waktu_selesai').textContent = data.waktu_selesai ?? '-';
        document.getElementById('detail_catatan').textContent = data.catatan ?? '-';

        const image = document.getElementById('detail_foto_odometer');
        const placeholder = document.getElementById('detail_foto_placeholder');

        if (data.foto_odometer) {
            image.src = data.foto_odometer;
            image.classList.remove('hidden');
            placeholder.classList.add('hidden');
        } else {
            image.src = '';
            image.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        const modal = document.getElementById('detailModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    /**
     * Tutup modal detail
     */
    function closeDetailModal() {
        const modal = document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /**
     * Tutup modal saat menekan tombol ESC
     */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDetailModal();
        }
    });
</script>
@endsection