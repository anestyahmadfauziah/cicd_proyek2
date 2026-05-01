<?php

namespace App\Http\Controllers;

use App\Models\Rekomendasi;
use App\Models\Destinasi;
use Illuminate\Http\Request;

class RekomendasiController extends Controller
{
    public function __construct()
    {
        // Hanya superadmin yang bisa CRUD
        $this->middleware('auth:superadmin')->except(['index']);
        // Semua user yang login bisa lihat index
        $this->middleware('auth')->only('index');
    }

    /**
     * INDEX - Semua user yang login bisa lihat
     */
    public function index()
    {
        $rekomendasi = Rekomendasi::with('destinasi')
            ->orderBy('urutan')
            ->get();

        // Tentukan prefix route untuk blade
        $prefix = request()->is('superadmin/*') ? 'superadmin' : 'admin';

        return view('rekomendasi.index', compact('rekomendasi', 'prefix'));
    }

    /**
     * FORM CREATE - superadmin only
     */
    public function create()
    {
        $destinasi = Destinasi::all();
        return view('rekomendasi.create', compact('destinasi'));
    }

    /**
     * STORE - superadmin only
     */
    public function store(Request $request)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi,id_destinasi',
            'urutan' => 'nullable|integer'
        ]);

        // Cegah duplikat
        if (Rekomendasi::where('destinasi_id', $request->destinasi_id)->exists()) {
            return back()->with('error', 'Destinasi sudah ada di rekomendasi!');
        }

        Rekomendasi::create([
            'destinasi_id' => $request->destinasi_id,
            'urutan' => $request->urutan ?? 0,
            'is_active' => true
        ]);

        $prefix = request()->is('superadmin/*') ? 'superadmin' : 'admin';
        return redirect()->route($prefix.'.rekomendasi.index')
            ->with('success', 'Rekomendasi berhasil ditambahkan');
    }

    /**
     * EDIT - superadmin only
     */
    public function edit($id)
    {
        $rekomendasi = Rekomendasi::findOrFail($id);
        $destinasi = Destinasi::all();

        return view('rekomendasi.edit', compact('rekomendasi', 'destinasi'));
    }

    /**
     * UPDATE - superadmin only
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'destinasi_id' => 'required|exists:destinasi,id_destinasi',
            'urutan' => 'nullable|integer',
            'is_active' => 'required|boolean'
        ]);

        $rekomendasi = Rekomendasi::findOrFail($id);

        $rekomendasi->update([
            'destinasi_id' => $request->destinasi_id,
            'urutan' => $request->urutan ?? 0,
            'is_active' => $request->is_active
        ]);

        $prefix = request()->is('superadmin/*') ? 'superadmin' : 'admin';
        return redirect()->route($prefix.'.rekomendasi.index')
            ->with('success', 'Rekomendasi berhasil diupdate');
    }

    /**
     * DELETE - superadmin only
     */
    public function destroy($id)
    {
        Rekomendasi::destroy($id);

        $prefix = request()->is('superadmin/*') ? 'superadmin' : 'admin';
        return redirect()->route($prefix.'.rekomendasi.index')
            ->with('success', 'Rekomendasi berhasil dihapus');
    }
}