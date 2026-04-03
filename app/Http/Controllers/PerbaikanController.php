<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Perbaikan;
use App\Models\MasterKend;
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
    public function create()
    {
        // Get vehicles that are NOT currently in repair
        $kendaraans = MasterKend::where('status', '!=', 'Perbaikan')
            ->orderBy('no_polisi')
            ->get();

        return view('admin.perbaikan.create', compact('kendaraans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kend' => 'required|exists:master_kends,id_kend',
            'tanggal_lapor' => 'required|date',
            'keluhan' => 'required',
            'catatan' => 'nullable',
        ]);

        // Check for active repair for this vehicle
        $activeRepair = Perbaikan::where('id_kend', $request->id_kend)
            ->whereIn('status', ['dilaporkan', 'diproses'])
            ->first();

        if ($activeRepair) {
            return back()->with('error', 'Kendaraan sedang dalam proses perbaikan')->withInput();
        }

        try {
            DB::beginTransaction();

            // Create repair record
            Perbaikan::create([
                'id_kend' => $request->id_kend,
                'tanggal_lapor' => $request->tanggal_lapor,
                'keluhan' => $request->keluhan,
                'status' => 'dilaporkan',
                'catatan' => $request->catatan,
            ]);

            // Update vehicle status
            $kendaraan = MasterKend::findOrFail($request->id_kend);
            $kendaraan->update(['status' => 'Perbaikan']);

            DB::commit();
            return redirect()->route('perbaikan.aktif')->with('success', 'Laporan perbaikan berhasil dibuat. Status kendaraan: Perbaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal membuat laporan: ' . $e->getMessage())->withInput();
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
                'catatan_tambahan' => 'nullable',
            ]);

            try {
                DB::beginTransaction();

                // Join catatan tambahan if provided
                $finalNote = $perbaikan->catatan;
                if ($request->catatan_tambahan) {
                    $finalNote .= "\n--- Penyelesaian ---\n" . $request->catatan_tambahan;
                }

                $perbaikan->update([
                    'status' => 'selesai',
                    'tgl_selesai' => $request->tgl_selesai,
                    'biaya' => $request->biaya,
                    'catatan' => $finalNote,
                ]);

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

            // Reset vehicle status if it was in repair
            $perbaikan->kendaraan->update(['status' => 'Tersedia']);
            
            $perbaikan->delete();

            DB::commit();
            return redirect()->route('perbaikan.aktif')->with('success', 'Laporan perbaikan dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}
