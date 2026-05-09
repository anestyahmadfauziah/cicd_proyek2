@extends('layouts.app')

@section('content')
<div class="container py-4">

    <h4 class="fw-bold mb-1" style="font-size: 1.4rem;">Tambah Rekomendasi</h4>
    <p class="text-muted mb-4" style="font-size: 0.85rem;">Pilih destinasi yang akan ditampilkan sebagai rekomendasi</p>

    <div class="card border-0 shadow-sm p-4" style="border-radius: 12px; max-width: 600px;">
        <form action="{{ route('superadmin.rekomendasi.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Destinasi</label>
                <select name="destinasi_id" class="form-select" style="font-size: 0.85rem;" required>
                    <option value="">-- Pilih Destinasi --</option>
                    @foreach($destinasi as $d)
                    <option value="{{ $d->id_destinasi }}">{{ $d->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: 0.85rem;">Urutan</label>
                <input type="number" name="urutan" class="form-control" style="font-size: 0.85rem;">
                <small class="text-muted" style="font-size: 0.75rem;">Isi urutan tampilan (opsional)</small>
            </div>

            <div class="d-flex gap-2">
                <button class="btn btn-primary btn-sm px-4" style="font-size: 0.85rem; border-radius: 8px;">Simpan</button>
                <a href="{{ route('superadmin.rekomendasi.index') }}" class="btn btn-secondary btn-sm px-3" style="font-size: 0.85rem; border-radius: 8px;">Kembali</a>
            </div>

        </form>
    </div>

</div>
@endsection