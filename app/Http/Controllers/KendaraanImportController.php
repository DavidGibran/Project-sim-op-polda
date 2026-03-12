<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\KendaraanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class KendaraanImportController extends Controller
{
    public function index()
    {
        return view('admin.kendaraan.import', [
            'title' => 'Import Data Kendaraan'
        ]);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ], [
            'file.required' => 'File Excel wajib diupload.',
            'file.mimes' => 'File harus bertipe xlsx atau xls.'
        ]);

        try {
            $file = $request->file('file');
            Excel::import(new KendaraanImport, $file);

            return redirect()->route('kendaraan.import')->with('success', 'Data kendaraan berhasil diimport.');
        } catch (\Exception $e) {
            Log::error('Import Error: ' . $e->getMessage());
            return redirect()->route('kendaraan.import')->with('error', 'Terjadi kesalahan saat mengimport data: ' . $e->getMessage());
        }
    }
}
