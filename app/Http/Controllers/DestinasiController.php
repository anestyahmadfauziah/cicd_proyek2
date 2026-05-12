<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DestinasiFoto;
use App\Models\DestinasiMedia;

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
            $user = auth()->guard('superadmin')->user();
            $role = 'superadmin';
            $query = Destinasi::with(['kategori','fotos']);
        } else {
            $user = auth()->user();
            $role = 'admin';
            $query = Destinasi::with(['kategori','fotos'])
                ->where('created_by_id', $user->id)
                ->where('created_by_role', $role);
        }

        $destinasi = $query
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

        if(auth()->guard('superadmin')->check()){
            $user = auth()->guard('superadmin')->user();
            $data['created_by_id'] = $user->id;
            $data['created_by_role'] = 'superadmin';
        } elseif(auth()->guard('admin_wisata')->check()){
            $user = auth()->guard('admin_wisata')->user();
            $data['created_by_id'] = $user->id;
            $data['created_by_role'] = 'admin';
        } else {
            abort(403);
        }

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('destinasi', 'public');
        }

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

        ActivityLog::create([
            'user_name' => $user->username ?? $user->name,
            'role' => $data['created_by_role'],
            'activity' => 'Menambahkan destinasi: ' . $destinasi->nama,
            'status' => 'Success'
        ]);

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
            'fotos.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000', // ← tambahan validasi video
        ]);

        // Update cover foto
        if ($request->hasFile('foto')) {
            if ($destinasi->foto) {
                Storage::disk('public')->delete($destinasi->foto);
            }
            $data['foto'] = $request->file('foto')->store('destinasi', 'public');
        }

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

        // Hapus foto slider yang dipilih
        if($request->hapus_foto){
            foreach($request->hapus_foto as $id_foto){
                $foto = DestinasiFoto::find($id_foto);
                if($foto){
                    Storage::disk('public')->delete($foto->foto);
                    $foto->delete();
                }
            }
        }

        // Upload foto slider baru
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

        // ===== UPLOAD VIDEO KE destinasi_media =====
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $path = $request->file('video')->store('destinasi_media', 'public');

            DestinasiMedia::create([
                'id_destinasi' => $destinasi->id_destinasi,
                'type'         => 'video',
                'url'          => asset('storage/' . $path),
            ]);
        }
        // ============================================

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