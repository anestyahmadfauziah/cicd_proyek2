<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DestinasiFoto;

class DestinasiController extends Controller
{
    private function getPrefix()
    {
        return auth('superadmin')->check() ? 'superadmin' : 'admin';
    }

    // LIST DESTINASI
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if(auth()->guard('superadmin')->check()){
            $id = auth()->guard('superadmin')->user()->id_superadmin;
            $role = 'superadmin';
        }else{
            $id = auth()->guard('web')->user()->id_admin;
            $role = 'admin';
        }

        $destinasi = Destinasi::with(['kategori','fotos'])
            ->where('created_by_id', $id)
            ->where('created_by_role', $role)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->whereRaw('LOWER(nama) LIKE ?', ['%' . strtolower($keyword) . '%'])
                      ->orWhereRaw('LOWER(lokasi) LIKE ?', ['%' . strtolower($keyword) . '%']);
                });
            })
            ->latest()
            ->paginate(6);

        $prefix = $this->getPrefix();

        return view('destinasi.index', compact('destinasi', 'keyword', 'prefix'));
    }

    // FORM CREATE
    public function create()
    {
        $kategori = Kategori::all();
        $prefix = $this->getPrefix();
        return view('destinasi.create', compact('kategori', 'prefix'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required',
            'lokasi' => 'required',
            'deskripsi' => 'required',
            'alamat_lengkap' => 'required',
            'jam_buka_weekday' => 'required',
            'jam_buka_weekend' => 'required',
            'harga_tiket_weekday' => 'required|numeric',
            'harga_tiket_weekend' => 'required|numeric',
            'id_kategori' => 'required',
            'foto' => 'required|image',
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // simpan creator
        if(auth()->guard('superadmin')->check()){
            $data['created_by_id'] = auth()->guard('superadmin')->user()->id_superadmin;
            $data['created_by_role'] = 'superadmin';
        }else{
            $data['created_by_id'] = auth()->guard('web')->user()->id_admin;
            $data['created_by_role'] = 'admin';
        }

        // cover foto
        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('destinasi', 'public');
        }

        // simpan destinasi
        $destinasi = Destinasi::create([
            'nama' => $data['nama'],
            'lokasi' => $data['lokasi'],
            'deskripsi' => $data['deskripsi'],
            'alamat_lengkap' => $data['alamat_lengkap'],
            'weekday' => $data['jam_buka_weekday'],
            'weekend' => $data['jam_buka_weekend'],
            'harga_tiket_weekday' => $data['harga_tiket_weekday'],
            'harga_tiket_weekend' => $data['harga_tiket_weekend'],
            'id_kategori' => $data['id_kategori'],
            'foto' => $data['foto'],
            'created_by_id' => $data['created_by_id'],
            'created_by_role' => $data['created_by_role'],
        ]);

        if (auth()->guard('superadmin')->check()) {
    $user = auth()->guard('superadmin')->user();
    $role = 'superadmin';
} else {
    $user = auth()->guard('web')->user();
    $role = 'admin';
}

ActivityLog::create([
    'user_name' => $user->username ?? $user->name,
    'role' => $role,
    'activity' => 'Menambahkan destinasi: ' . $destinasi->nama,
    'status' => 'Success'
]);

        // ================= SIMPAN MULTI FOTO =================
        if($request->hasFile('fotos')){
            foreach($request->file('fotos') as $file){
                $path = $file->store('destinasi', 'public');

                DestinasiFoto::create([
                    'id_destinasi' => $destinasi->id_destinasi,
                    'foto' => $path
                ]);
            }
        }

        return redirect()->route($this->getPrefix().'.destinasi.index')
            ->with('success', 'Destinasi berhasil ditambahkan!');
    }

    // EDIT
    public function edit(Destinasi $destinasi)
    {
        $kategori = Kategori::all();
        $prefix = $this->getPrefix();

        $destinasi->load('fotos');

        return view('destinasi.edit', compact('destinasi', 'kategori', 'prefix'));
    }

    // UPDATE
    public function update(Request $request, $id)
{
    $destinasi = Destinasi::findOrFail($id);

    // ================= VALIDASI =================
    $data = $request->validate([
        'nama' => 'required',
        'lokasi' => 'required',
        'deskripsi' => 'required',
        'alamat_lengkap' => 'required',
        'jam_buka_weekday' => 'required',
        'jam_buka_weekend' => 'required',
        'harga_tiket_weekday' => 'required|numeric',
        'harga_tiket_weekend' => 'required|numeric',
        'id_kategori' => 'required',
        'foto' => 'nullable|image',
        'fotos' => 'nullable|array',
        'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // ================= UPDATE COVER =================
    if ($request->hasFile('foto')) {

        // hapus foto lama
        if ($destinasi->foto) {
            Storage::disk('public')->delete($destinasi->foto);
        }

        // upload baru
        $data['foto'] = $request->file('foto')->store('destinasi', 'public');
    }

    // ================= UPDATE DATA =================
    $destinasi->update([
        'nama' => $data['nama'],
        'lokasi' => $data['lokasi'],
        'deskripsi' => $data['deskripsi'],
        'alamat_lengkap' => $data['alamat_lengkap'],
        'weekday' => $data['jam_buka_weekday'],
        'weekend' => $data['jam_buka_weekend'],
        'harga_tiket_weekday' => $data['harga_tiket_weekday'],
        'harga_tiket_weekend' => $data['harga_tiket_weekend'],
        'id_kategori' => $data['id_kategori'],
        'foto' => $data['foto'] ?? $destinasi->foto,
    ]);

    // ================= HAPUS FOTO SLIDER (OPTIONAL) =================
    if($request->hapus_foto){
        foreach($request->hapus_foto as $id_foto){
            $foto = DestinasiFoto::find($id_foto);

            if($foto){
                Storage::disk('public')->delete($foto->foto);
                $foto->delete();
            }
        }
    }

    // ================= TAMBAH FOTO SLIDER BARU =================
    if($request->hasFile('fotos')){
        foreach($request->file('fotos') as $file){

            if($file->isValid()){
                $path = $file->store('destinasi', 'public');

                DestinasiFoto::create([
                    'id_destinasi' => $destinasi->id_destinasi,
                    'foto' => $path
                ]);
            }
        }
    }

    return redirect()->route($this->getPrefix().'.destinasi.index')
        ->with('success', 'Destinasi berhasil diupdate!');
}

    // DELETE
    public function destroy(Destinasi $destinasi)
    {
        if ($destinasi->foto) {
            Storage::disk('public')->delete($destinasi->foto);
        }

        foreach($destinasi->fotos as $foto){
            Storage::disk('public')->delete($foto->foto);
        }

        $destinasi->delete();

        return redirect()->route($this->getPrefix().'.destinasi.index')
            ->with('success', 'Destinasi berhasil dihapus!');
    }
}