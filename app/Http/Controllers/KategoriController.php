<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Kategori::create(['nama_kategori' => $request->nama_kategori]);
        return redirect()->back()->with('success_kategori', 'Kategori berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255']);
        Kategori::where('id_kategori', $id)->update(['nama_kategori' => $request->nama_kategori]);
        return redirect()->back()->with('success_kategori', 'Kategori berhasil diupdate');
    }

    public function destroy($id)
    {
        Kategori::where('id_kategori', $id)->delete();
        return redirect()->back()->with('success_kategori', 'Kategori berhasil dihapus');
    }
}