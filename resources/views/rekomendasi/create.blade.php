@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Tambah Rekomendasi</h2>

    <form action="{{ route('superadmin.rekomendasi.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Destinasi</label>
            <select name="destinasi_id" class="form-control" required>
                <option value="">-- Pilih Destinasi --</option>
                @foreach($destinasi as $d)
                <option value="{{ $d->id_destinasi }}">{{ $d->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Urutan</label>
            <input type="number" name="urutan" class="form-control">
        </div>

        <button class="btn btn-success">Simpan</button>
        <a href="{{ route('superadmin.rekomendasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection