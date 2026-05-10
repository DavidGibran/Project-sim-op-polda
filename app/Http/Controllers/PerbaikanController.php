<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perbaikan;
use App\Models\MasterKend;
use App\Models\LaporanKerusakan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


//include privat method - service
use App\Services\PerbaikanServices;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('perbaikan.aktif');
    }

    /**
     * Display ACTIVE repairs (dilaporkan, diproses).
     */
    public function aktif(Request $request)
    {
        $status = $request->query('status');
        $perPage = $request->query('per_page', 10);

        $query = Perbaikan::with('kendaraan')
            ->whereIn('status', ['dilaporkan', 'diproses']);

        if ($status) {
            $query->where('status', $status);
        }

        $perbaikans = $query->latest('tanggal_lapor')->paginate($perPage)->withQueryString();

        return view('admin.perbaikan.aktif', compact('perbaikans', 'status', 'perPage'));
    }

    /**
     * Display COMPLETED repairs (selesai).
     */
    //Privat method - service (buat reuse di laporan)
    public function __construct(protected PerbaikanServices $service) {}

    public function riwayat(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        // query dipindah ke service
        $perbaikans = $this->service->getRiwayat($search, $perPage);

        return view('admin.perbaikan.riwayat', compact('perbaikans', 'search', 'perPage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $laporan_id = $request->query('laporan_id');
        $laporan = null;
        $laporans = null;

        if ($laporan_id) {
            $laporan = LaporanKerusakan::with('kendaraan')->findOrFail($laporan_id);
            
            // Validasi: Laporan harus berstatus diterbitkan
            if ($laporan->status !== 'diterbitkan') {
                return redirect()->route('admin.laporan-kerusakan.index')
                    ->with('error', 'Laporan kerusakan ini sudah diproses atau selesai.');
            }
        } else {
            // Jika tidak ada laporan_id, ambil semua laporan yang berstatus diterbitkan
            $laporans = LaporanKerusakan::with('kendaraan')
                ->where('status', 'diterbitkan')
                ->latest()
                ->get();
        }

        return view('admin.perbaikan.create', compact('laporan', 'laporans'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_laporan' => 'required|exists:tb_laporan_kerusakans,id',
            'teknisi' => 'required|string',
            'biaya' => 'nullable|numeric',
            'tgl_mulai' => 'required|date',
            'detail_perbaikan' => 'required|string',
        ]);

        $laporan = LaporanKerusakan::findOrFail($request->id_laporan);

        try {
            DB::beginTransaction();

            $perbaikan = Perbaikan::create([
                'id_kend' => $laporan->id_kend,
                'id_laporan' => $laporan->id,
                'tanggal_lapor' => $laporan->tanggal_lapor,
                'keluhan' => $laporan->keluhan,
                'status' => 'diproses',
                'teknisi' => $request->teknisi,
                'biaya' => $request->biaya ?? 0,
                'tgl_mulai' => $request->tgl_mulai,
                'catatan' => $request->detail_perbaikan,
            ]);

            // Update Laporan status
            $laporan->update(['status' => 'diproses']);


            // Update vehicle status
            $laporan->kendaraan->update(['status' => 'Perbaikan']);

            DB::commit();
            return redirect()->route('perbaikan.aktif')->with('success', 'Perbaikan berhasil diproses. Status laporan: Diproses.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses perbaikan: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Perbaikan $perbaikan)
    {
        $perbaikan->load('kendaraan');

        // tentukan route kembali berdasarkan ?from
        $backRoute = request('from') === 'laporan'
        ? route('laporan.perbaikan')
        : route('perbaikan.riwayat');

        return view('admin.perbaikan.show', compact('perbaikan', 'backRoute'));
    }

    /**
     * Update the specified resource (Status changes).
     */
    public function update(Request $request, Perbaikan $perbaikan)
    {
        $action = $request->input('action');

        if ($action === 'mulai') {
            if ($perbaikan->status !== 'dilaporkan') {
                return back()->with('error', 'Status tidak valid untuk aksi ini.');
            }

            $perbaikan->update([
                'status' => 'diproses',
                'tgl_mulai' => now(),
                'teknisi' => $request->input('teknisi', 'Internal'),
            ]);

            return back()->with('success', 'Proses perbaikan dimulai.');
        }

        if ($action === 'selesai') {
            if ($perbaikan->status !== 'diproses') {
                return back()->with('error', 'Status tidak valid untuk aksi ini.');
            }

            $request->validate([
                'tgl_selesai' => 'required|date',
                'biaya' => 'required|numeric',
            ]);

            try {
                DB::beginTransaction();

                $perbaikan->update([
                    'status' => 'selesai',
                    'tgl_selesai' => $request->tgl_selesai,
                    'biaya' => $request->biaya,
                ]);

                // Update Laporan status
                if ($perbaikan->id_laporan) {
                    $perbaikan->laporan->update(['status' => 'selesai']);
                }

                // Update vehicle status back to Tersedia
                $perbaikan->kendaraan->update(['status' => 'Tersedia']);


                DB::commit();
                return redirect()->route('perbaikan.riwayat')->with('success', 'Perbaikan selesai. Kendaraan kini Tersedia.');
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->with('error', 'Gagal menyelesaikan perbaikan: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'Aksi tidak dikenal.');
    }

    /**
     * Remove the specified resource from storage (Delete/Cancel).
     */
    public function destroy(Perbaikan $perbaikan)
    {
        if ($perbaikan->status === 'selesai') {
            return back()->with('error', 'Riwayat perbaikan yang sudah selesai tidak bisa dihapus.');
        }

        try {
            DB::beginTransaction();

            $laporan = $perbaikan->laporan;
            $kendaraan = $perbaikan->kendaraan;

            // Delete repair record
            $perbaikan->delete();

            // Jika ada laporan terkait, kembalikan statusnya ke diterbitkan
            if ($laporan) {
                $laporan->update(['status' => 'diterbitkan']);
            }

            // Kembalikan status kendaraan ke Tersedia
            if ($kendaraan) {
                $kendaraan->update(['status' => 'Tersedia']);
            }

            DB::commit();

            return redirect()->route('perbaikan.aktif')
                ->with('success', 'Data perbaikan berhasil dihapus dan status laporan dikembalikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }
}
