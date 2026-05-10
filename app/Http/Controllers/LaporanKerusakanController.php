<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerusakan;
use App\Models\MasterKend;
use App\Models\Penugasan;
use Illuminate\Http\Request;


class LaporanKerusakanController extends Controller
{
    /**
     * Tampilkan daftar laporan kerusakan
     */
    public function index(Request $request)
    {
        $status = $request->query('status');
        $mode = $request->query('mode');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = LaporanKerusakan::with(['kendaraan', 'perbaikan'])
            ->whereIn('status', ['diterbitkan', 'diproses']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($mode) {
            $query->where('mode', $mode);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('no_laporan', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function($kq) use ($search) {
                      $kq->where('no_polisi', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                  });
            });
        }

        $laporans = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.laporan-kerusakan.index', compact('laporans', 'status', 'mode', 'search', 'perPage'));
    }

    /**
     * Display a listing of COMPLETED reports (Riwayat).
     */
    public function riwayat(Request $request)
    {
        $mode = $request->query('mode');
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $query = LaporanKerusakan::with(['kendaraan', 'perbaikan'])
            ->where('status', 'selesai');

        if ($mode) {
            $query->where('mode', $mode);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('no_laporan', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function($kq) use ($search) {
                      $kq->where('no_polisi', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                  });
            });
        }

        $laporans = $query->latest()->paginate($perPage)->withQueryString();

        return view('admin.laporan-kerusakan.riwayat', compact('laporans', 'mode', 'search', 'perPage'));
    }


    /**
     * Form tambah laporan (Mode Detail - Admin)
     */
    public function create()
    {
        $kendaraans = MasterKend::orderBy('no_polisi')->get();
        
        // Ambil penugasan yang aktif atau baru diterbitkan (opsional untuk dikaitkan)
        $penugasans = Penugasan::whereIn('status', ['diterbitkan', 'diterima', 'berjalan'])
            ->latest()
            ->get();

        return view('admin.laporan-kerusakan.create', compact('kendaraans', 'penugasans'));
    }


    /**
     * Simpan laporan baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kend' => 'required|exists:master_kends,id_kend',
            'keluhan' => 'required|string',
            'detail_teknis' => 'nullable|string',
        ]);

        $maxRetries = 5;
        $attempt = 0;
        $success = false;

        while ($attempt < $maxRetries && !$success) {
            try {
                LaporanKerusakan::create([
                    'no_laporan' => LaporanKerusakan::generateNoLaporan(),
                    'id_kend' => $request->id_kend,
                    'id_penugasan' => null, 
                    'sumber' => 'admin',
                    'mode' => 'detail',
                    'keluhan' => $request->keluhan,
                    'detail_teknis' => $request->detail_teknis,
                    'nomor_hp' => null, 
                    'status' => 'diterbitkan',
                    'tanggal_lapor' => now(),
                ]);
                $success = true;
            } catch (\Illuminate\Database\QueryException $e) {
                if ($e->errorInfo[1] == 1062) { // MySQL unique constraint error code
                    $attempt++;
                    if ($attempt >= $maxRetries) throw $e;
                    usleep(100000); // Wait 100ms before retry
                } else {
                    throw $e;
                }
            }
        }




        return redirect()->route('admin.laporan-kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil diterbitkan.');
    }

    /**
     * Detail laporan
     */
    public function show(LaporanKerusakan $laporanKerusakan)
    {
        $laporanKerusakan->load(['kendaraan', 'penugasan', 'perbaikan']);
        return view('admin.laporan-kerusakan.show', [
            'laporan' => $laporanKerusakan
        ]);
    }

    /**
     * Form edit laporan
     */
    public function edit(LaporanKerusakan $laporanKerusakan)
    {
        $kendaraans = MasterKend::orderBy('no_polisi')->get();
        $penugasans = Penugasan::whereIn('status', ['diterbitkan', 'diterima', 'berjalan', 'selesai', 'dibatalkan'])
            ->where('id_kend', $laporanKerusakan->id_kend)
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.laporan-kerusakan.edit', [
            'laporan' => $laporanKerusakan,
            'kendaraans' => $kendaraans,
            'penugasans' => $penugasans
        ]);
    }


    /**
     * Update laporan
     */
    public function update(Request $request, LaporanKerusakan $laporanKerusakan)
    {
        $request->validate([
            'id_kend' => 'required|exists:master_kends,id_kend',
            'keluhan' => 'required|string',
            'detail_teknis' => 'nullable|string',
        ]);

        $laporanKerusakan->update([
            'id_kend' => $request->id_kend,
            'keluhan' => $request->keluhan,
            'detail_teknis' => $request->detail_teknis,
            // id_penugasan dan nomor_hp tetap pada nilai aslinya jika ada (misal diubah dari admin)
        ]);



        return redirect()->route('admin.laporan-kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil diperbarui.');
    }

    /**
     * Hapus laporan (jika belum diproses)
     */
    public function destroy(LaporanKerusakan $laporanKerusakan)
    {
        if ($laporanKerusakan->status != 'diterbitkan') {
            return back()->with('error', 'Laporan yang sudah diproses tidak dapat dihapus.');
        }

        $laporanKerusakan->delete();

        return redirect()->route('admin.laporan-kerusakan.index')
            ->with('success', 'Laporan kerusakan berhasil dihapus.');
    }
}
