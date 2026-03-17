<?php

namespace App\Http\Controllers;

use App\Models\Penugasan;
use App\Models\MasterKend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PenugasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Penugasan::with('kendaraan');

        // Optional filtering by status
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Search feature
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tujuan', 'like', "%{$search}%")
                  ->orWhere('pengemudi', 'like', "%{$search}%")
                  ->orWhereHas('kendaraan', function($k) use ($search) {
                      $k->where('no_polisi', 'like', "%{$search}%")
                        ->orWhere('merk', 'like', "%{$search}%")
                        ->orWhere('tipe', 'like', "%{$search}%");
                  });
            });
        }

        $penugasans = $query->latest('tgl_tugas')->paginate(10)->withQueryString();
        
        return view('admin.penugasan.index', compact('penugasans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kendaraans = MasterKend::where('status', 'Tersedia')->get();
        return view('admin.penugasan.create', compact('kendaraans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_kend'   => 'required|exists:master_kends,id_kend',
            'tujuan'    => 'required|string|max:255',
            'pengemudi' => 'nullable|string|max:255',
            'tgl_tugas' => 'required|date',
            'catatan'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $kendaraan = MasterKend::findOrFail($request->id_kend);

            // Create penugasan
            Penugasan::create([
                'id_kend'   => $kendaraan->id_kend,
                'tujuan'    => $request->tujuan,
                'pengemudi' => $request->pengemudi,
                'tgl_tugas' => $request->tgl_tugas,
                'km_awal'   => $kendaraan->km_terakhir,
                'status'    => 'diterbitkan',
                'catatan'   => $request->catatan,
            ]);

            // Update kendaraan status
            $kendaraan->update(['status' => 'Diterbitkan']);

            DB::commit();
            return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating penugasan: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Gagal membuat penugasan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $penugasan = Penugasan::with('kendaraan')->findOrFail($id);
        return view('admin.penugasan.show', compact('penugasan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $penugasan = Penugasan::findOrFail($id);
        
        // Allowed to edit only if pending or active perhaps, but the specification implies admin can edit specific fields
        
        return view('admin.penugasan.edit', compact('penugasan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'tujuan'    => 'required|string|max:255',
            'pengemudi' => 'nullable|string|max:255',
            'tgl_tugas' => 'required|date',
            'catatan'   => 'nullable|string',
        ]);

        $penugasan = Penugasan::findOrFail($id);
        
        $penugasan->update([
            'tujuan'    => $request->tujuan,
            'pengemudi' => $request->pengemudi,
            'tgl_tugas' => $request->tgl_tugas,
            'catatan'   => $request->catatan,
        ]);

        return redirect()->route('penugasan.index')->with('success', 'Data penugasan berhasil diperbarui.');
    }

    /**
     * Membatalkan penugasan dan mengembalikan status kendaraan
     */
    public function batalkan(string $id)
    {
        DB::beginTransaction();
        try {
            $penugasan = Penugasan::findOrFail($id);
            
            // Allow cancel only if not already cancelled or selesai
            if (in_array($penugasan->status, ['selesai', 'dibatalkan'])) {
                return redirect()->route('penugasan.index')->with('error', 'Penugasan tidak dapat dibatalkan karena sudah selesai atau dibatalkan.');
            }

            $penugasan->update(['status' => 'dibatalkan']);
            
            // Restore vehicle to Tersedia
            if ($penugasan->kendaraan) {
                $penugasan->kendaraan->update(['status' => 'Tersedia']);
            }

            DB::commit();
            return redirect()->route('penugasan.index')->with('success', 'Penugasan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('penugasan.index')->with('error', 'Gagal membatalkan penugasan: ' . $e->getMessage());
        }
    }

    /**
     * (Optional) Remove the specified resource from storage if needed. Let's keep it just in case, or default to fallback.
     */
    public function destroy(string $id)
    {
        // For deleting the record entirely if needed
        $penugasan = Penugasan::findOrFail($id);
        $penugasan->delete();
        
        return redirect()->route('penugasan.index')->with('success', 'Data penugasan berhasil dihapus permanen.');
    }
}
