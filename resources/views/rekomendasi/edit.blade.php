@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Rekomendasi</h2>

    <form action="{{ route('superadmin.rekomendasi.update', $rekomendasi->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Destinasi</label>
            <select name="destinasi_id" class="form-control" required>
                @foreach($destinasi as $d)
                    <option value="{{ $d->id_destinasi }}"
                        {{ $rekomendasi->destinasi_id == $d->id_destinasi ? 'selected' : '' }}>
                        {{ $d->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Urutan</label>
            <input type="number" name="urutan" class="form-control" value="{{ $rekomendasi->urutan }}">
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="is_active" class="form-control">
                <option value="1" {{ $rekomendasi->is_active ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ !$rekomendasi->is_active ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('superadmin.rekomendasi.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection