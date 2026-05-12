<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destinasi;
use App\Models\DestinasiMedia;
use Illuminate\Support\Facades\Storage;

class DestinasiMediaController extends Controller
{
    public function index($id)
    {
        $destinasi = Destinasi::with('media')->findOrFail($id);

        return view('destinasi.media', compact('destinasi'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|file|mimes:mp4,mov,avi,wmv|max:512000',
        ]);

        $destinasi = Destinasi::findOrFail($id);

        $path = $request->file('file')->store('destinasi_media', 'public');

        DestinasiMedia::create([
            'id_destinasi' => $destinasi->id_destinasi,
            'type'         => 'video',
            'url'          => asset('storage/' . $path),
        ]);

        return back()->with('success', 'Video berhasil diupload');
    }

    public function destroy($id)
    {
        $media = DestinasiMedia::findOrFail($id);

        // Hapus file dari storage
        $path = str_replace(asset('storage/'), '', $media->url);
        Storage::disk('public')->delete($path);

        $media->delete();

        return back()->with('success', 'Video berhasil dihapus');
    }

    public function api($id)
    {
        $data = DestinasiMedia::where('id_destinasi', $id)
            ->get();

        return response()->json($data);
    }
}