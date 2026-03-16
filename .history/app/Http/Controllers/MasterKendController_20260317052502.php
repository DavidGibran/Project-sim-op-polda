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

        $kendaraans = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

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
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kategori_kendaraan' => 'required|string|max:50',
            'jenis_kendaraan' => 'required|string|max:255',
            'keterangan_penggunaan' => 'nullable|string',
            'km_terakhir' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipakai,Perbaikan',
        ]);

        $data = $request->all();
        // Generate default authentication fields for the vehicle
        $data['username'] = strtolower(str_replace(' ', '', $request->no_polisi));
        $data['password'] = Hash::make('password123'); // Default standard password
        
        MasterKend::create($data);

        return redirect()->route('kendaraan.index')->with('success', 'Data Kendaraan berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kendaraan = MasterKend::with([
            'penugasans' => function($query) {
                $query->latest('tgl_tugas')->take(5);
            },
            'perbaikans' => function($query) {
                $query->latest('tanggal_laporan')->take(5);
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
            'merk' => 'required|string|max:255',
            'tipe' => 'required|string|max:255',
            'tahun' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'kategori_kendaraan' => 'required|string|max:50',
            'jenis_kendaraan' => 'required|string|max:255',
            'keterangan_penggunaan' => 'nullable|string',
            'km_terakhir' => 'required|integer|min:0',
            'status' => 'required|in:Tersedia,Dipakai,Perbaikan',
        ]);

        $kendaraan->update($request->all());

        return redirect()->route('kendaraan.index')->with('success', 'Data Kendaraan berhasil diperbarui.');
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
}
