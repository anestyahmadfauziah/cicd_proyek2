<?php

namespace App\Http\Controllers;

use App\Models\Destinasi;
use App\Models\Kategori;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use App\Models\DestinasiFoto;
use App\Models\DestinasiMedia;
use App\Services\SupabaseStorage;

class DestinasiController extends Controller
{
    protected SupabaseStorage $supabase;

    public function __construct()
    {
        $this->supabase = new SupabaseStorage();
    }

    private function getPrefix()
    {
        return auth('superadmin')->check() ? 'superadmin' : 'admin';
    }

    // LIST DESTINASI
    public function index(Request $request)
    {
        $keyword = $request->keyword;

        if (auth()->guard('superadmin')->check()) {
            $user  = auth()->guard('superadmin')->user();
            $role  = 'superadmin';
            $query = Destinasi::with(['kategori', 'fotos']);
        } else {
            $user  = auth()->user();
            $role  = 'admin';
            $query = Destinasi::with(['kategori', 'fotos'])
                ->where('created_by_id', $user->id)
                ->where('created_by_role', $role);
        }

        $destinasi = $query
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
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
        $prefix   = $this->getPrefix();
        return view('destinasi.create', compact('kategori', 'prefix'));
    }

    // STORE
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'                => 'required',
            'lokasi'              => 'required',
            'deskripsi'           => 'required',
            'alamat_lengkap'      => 'required',
            'jam_buka_weekday'    => 'required',
            'jam_buka_weekend'    => 'required',
            'harga_tiket_weekday' => 'required|numeric',
            'harga_tiket_weekend' => 'required|numeric',
            'id_kategori'         => 'required',
            'foto'                => 'required|image|max:5120',
            'fotos.*'             => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Tentukan user yang login
        if (auth()->guard('superadmin')->check()) {
            $user                    = auth()->guard('superadmin')->user();
            $data['created_by_id']   = $user->id;
            $data['created_by_role'] = 'superadmin';
        } elseif (auth()->guard('admin_wisata')->check()) {
            $user                    = auth()->guard('admin_wisata')->user();
            $data['created_by_id']   = $user->id;
            $data['created_by_role'] = 'admin';
        } else {
            abort(403);
        }

        // Upload foto cover ke Supabase
        $fotoUrl = $this->supabase->upload($request->file('foto'), 'covers');

        // Simpan destinasi ke database
        $destinasi = Destinasi::create([
            'nama'                => $data['nama'],
            'lokasi'              => $data['lokasi'],
            'deskripsi'           => $data['deskripsi'],
            'alamat_lengkap'      => $data['alamat_lengkap'],
            'weekday'             => $data['jam_buka_weekday'],
            'weekend'             => $data['jam_buka_weekend'],
            'harga_tiket_weekday' => $data['harga_tiket_weekday'],
            'harga_tiket_weekend' => $data['harga_tiket_weekend'],
            'id_kategori'         => $data['id_kategori'],
            'foto'                => $fotoUrl, // URL lengkap Supabase
            'created_by_id'       => $data['created_by_id'],
            'created_by_role'     => $data['created_by_role'],
        ]);

        // Catat activity log
        ActivityLog::create([
            'user_name' => $user->username ?? $user->name,
            'role'      => $data['created_by_role'],
            'activity'  => 'Menambahkan destinasi: ' . $destinasi->nama,
            'status'    => 'Success',
        ]);

        // Upload foto slider ke Supabase
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                $sliderUrl = $this->supabase->upload($file, 'sliders');
                DestinasiFoto::create([
                    'id_destinasi' => $destinasi->id_destinasi,
                    'foto'         => $sliderUrl,
                ]);
            }
        }

        return redirect()->route($this->getPrefix() . '.destinasi.index')
            ->with('success', 'Destinasi berhasil ditambahkan!');
    }

    // EDIT
    public function edit(Destinasi $destinasi)
    {
        $kategori = Kategori::all();
        $prefix   = $this->getPrefix();
        $destinasi->load('fotos');
        return view('destinasi.edit', compact('destinasi', 'kategori', 'prefix'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $destinasi = Destinasi::findOrFail($id);

        $data = $request->validate([
            'nama'                => 'required',
            'lokasi'              => 'required',
            'deskripsi'           => 'required',
            'alamat_lengkap'      => 'required',
            'jam_buka_weekday'    => 'required',
            'jam_buka_weekend'    => 'required',
            'harga_tiket_weekday' => 'required|numeric',
            'harga_tiket_weekend' => 'required|numeric',
            'id_kategori'         => 'required',
            'foto'                => 'nullable|image|max:5120',
            'fotos.*'             => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'video'               => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000',
        ]);

        // Ganti foto cover jika ada upload baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari Supabase
            if ($destinasi->foto) {
                $this->supabase->delete($destinasi->foto);
            }
            $data['foto'] = $this->supabase->upload($request->file('foto'), 'covers');
        }

        $destinasi->update([
            'nama'                => $data['nama'],
            'lokasi'              => $data['lokasi'],
            'deskripsi'           => $data['deskripsi'],
            'alamat_lengkap'      => $data['alamat_lengkap'],
            'weekday'             => $data['jam_buka_weekday'],
            'weekend'             => $data['jam_buka_weekend'],
            'harga_tiket_weekday' => $data['harga_tiket_weekday'],
            'harga_tiket_weekend' => $data['harga_tiket_weekend'],
            'id_kategori'         => $data['id_kategori'],
            'foto'                => $data['foto'] ?? $destinasi->foto,
        ]);

        // Hapus foto slider yang dipilih
        if ($request->hapus_foto) {
            foreach ($request->hapus_foto as $id_foto) {
                $foto = DestinasiFoto::find($id_foto);
                if ($foto) {
                    $this->supabase->delete($foto->foto);
                    $foto->delete();
                }
            }
        }

        // Upload foto slider baru
        if ($request->hasFile('fotos')) {
            foreach ($request->file('fotos') as $file) {
                if ($file->isValid()) {
                    $sliderUrl = $this->supabase->upload($file, 'sliders');
                    DestinasiFoto::create([
                        'id_destinasi' => $destinasi->id_destinasi,
                        'foto'         => $sliderUrl,
                    ]);
                }
            }
        }

        // Upload video
        if ($request->hasFile('video') && $request->file('video')->isValid()) {
            $videoUrl = $this->supabase->upload($request->file('video'), 'videos');
            DestinasiMedia::create([
                'id_destinasi' => $destinasi->id_destinasi,
                'type'         => 'video',
                'url'          => $videoUrl,
            ]);
        }

        return redirect()->route($this->getPrefix() . '.destinasi.index')
            ->with('success', 'Destinasi berhasil diupdate!');
    }

    // DELETE
    public function destroy(Destinasi $destinasi)
    {
        // Hapus foto cover dari Supabase
        if ($destinasi->foto) {
            $this->supabase->delete($destinasi->foto);
        }

        // Hapus semua foto slider dari Supabase
        foreach ($destinasi->fotos as $foto) {
            $this->supabase->delete($foto->foto);
        }

        $destinasi->delete();

        return redirect()->route($this->getPrefix() . '.destinasi.index')
            ->with('success', 'Destinasi berhasil dihapus!');
    }
}