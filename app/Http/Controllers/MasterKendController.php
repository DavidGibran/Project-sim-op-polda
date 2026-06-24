<?php

namespace App\Http\Controllers;

use App\Models\MasterKend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MasterKendController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = MasterKend::query();

        // Search feature based on no_polisi, merk, or tipe
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_polisi', 'like', "%{$search}%")
                  ->orWhere('merk', 'like', "%{$search}%")
                  ->orWhere('tipe', 'like', "%{$search}%");
            });
        }

        // Status Filter feature
        if ($request->filled('status') && $request->status !== 'Semua') {
            $query->where('status', $request->status);
        }

        // Sort feature
        $sortBy = $request->input('sort_by', 'created_at');
        $sortDir = $request->input('sort_dir', 'desc');

        $allowableSorts = ['no_polisi', 'merk', 'tahun', 'km_terakhir', 'status'];
        if (in_array($sortBy, $allowableSorts)) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $perPage = $request->input('per_page', 10);
        $kendaraans = $query->paginate($perPage)->withQueryString();

        return view('admin.kendaraan.index', compact('kendaraans'))->with('title', 'Master Kendaraan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.kendaraan.create')->with('title', 'Tambah Kendaraan Baru');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'no_polisi' => 'required|string|max:255|unique:master_kends,no_polisi',
            'nama_pemegang' => 'nullable|string|max:255',
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'nama_pada_simak' => 'nullable|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'bbm' => 'nullable|string|max:255',
            'kategori_kendaraan' => 'required|string|max:50',
            'jenis_kendaraan' => 'required|in:RANUM,RANSUS,LAINNYA',
            'keterangan_penggunaan' => 'nullable|string',
            'km_terakhir' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipakai,Perbaikan',
        ], [
            'no_polisi.unique' => 'Nomor Polisi ini sudah digunakan. Silakan cek kembali database atau gunakan nomor lain.',
            'jenis_kendaraan.in' => 'Jenis kendaraan harus RANUM, RANSUS, atau LAINNYA.',
        ]);

        $data = $request->all();
        // Generate default authentication fields for the vehicle
        $data['username'] = strtolower(str_replace(' ', '', $request->no_polisi));
        $data['password'] = Hash::make('password123'); // Default standard password
        
        // Ensure defaults for optional fields
        if (empty($data['nama_pada_simak'])) $data['nama_pada_simak'] = '-';
        if (empty($data['nama_pemegang'])) $data['nama_pemegang'] = '-';
        if (empty($data['bbm'])) $data['bbm'] = '-';
        if (empty($data['keterangan_penggunaan'])) $data['keterangan_penggunaan'] = '-';
        
        MasterKend::create($data);

        return redirect()->route('kendaraan.index')
            ->with('success', 'Data Kendaraan berhasil ditambahkan ke database.')
            ->with('title', 'Registrasi Berhasil'); // Opsional: Custom Title
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kendaraan = MasterKend::with([
            'penugasans' => function($query) {
                $query->latest('tgl_tugas')->take(3);
            },
            'perbaikans' => function($query) {
                $query->latest('tanggal_lapor')->take(3);
            }
        ])->findOrFail($id);
        
        return view('admin.kendaraan.show', compact('kendaraan'))->with('title', 'Detail Kendaraan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kendaraan = MasterKend::findOrFail($id);
        return view('admin.kendaraan.edit', compact('kendaraan'))->with('title', 'Edit Data Kendaraan');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kendaraan = MasterKend::findOrFail($id);

        $request->validate([
            'no_polisi' => [
                'required',
                'string',
                'max:255',
                Rule::unique('master_kends')->ignore($kendaraan->id_kend, 'id_kend'),
            ],
            'nama_pemegang' => 'nullable|string|max:255',
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'nama_pada_simak' => 'nullable|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'bbm' => 'nullable|string|max:255',
            'kategori_kendaraan' => 'required|string|max:50',
            'jenis_kendaraan' => 'required|in:RANUM,RANSUS,LAINNYA',
            'keterangan_penggunaan' => 'nullable|string',
            'km_terakhir' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipakai,Perbaikan',
        ], [
            'no_polisi.unique' => 'Nomor Polisi ini sudah digunakan oleh kendaraan lain.',
            'jenis_kendaraan.in' => 'Jenis kendaraan harus RANUM, RANSUS, atau LAINNYA.',
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('kendaraan.index')
            ->with('success', 'Perubahan data kendaraan telah disimpan.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kendaraan = MasterKend::findOrFail($id);
        $kendaraan->delete();

        return redirect()->route('kendaraan.index')->with('success', 'Data Kendaraan berhasil dihapus.');
    }

    /**
     * AJAX: Cek ketersediaan No Polisi secara realtime
     */
    public function checkAvailability(Request $request)
    {
        $noPolisi = $request->input('no_polisi');
        $excludeId = $request->input('exclude_id');

        $query = MasterKend::where('no_polisi', $noPolisi);

        if ($excludeId) {
            $query->where('id_kend', '!=', $excludeId);
        }

        return response()->json(['exists' => $query->exists()]);
    }
}
