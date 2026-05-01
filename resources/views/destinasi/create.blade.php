@extends('layouts.app')

@section('content')

@php
$prefix = auth('superadmin')->check() ? 'superadmin' : 'admin';
@endphp

<div class="container mt-5">
    <h2 class="text-center mb-4">Tambah Destinasi Wisata</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif

   <form action="{{ route($prefix.'.destinasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Nama Destinasi</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" required>
            @error('nama')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Lokasi</label>
            <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi') }}" required>
            @error('lokasi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Deskripsi</label>
            <textarea name="deskripsi" class="form-control" required>{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label>Alamat Lengkap</label>
            <textarea name="alamat_lengkap" class="form-control" required>{{ old('alamat_lengkap') }}</textarea>
            @error('alamat_lengkap')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Jam Buka Weekday</label>
                <input type="text" name="jam_buka_weekday" class="form-control" placeholder="08:00 - 17:00" value="{{ old('jam_buka_weekday') }}">
                @error('jam_buka_weekday')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Jam Buka Weekend</label>
                <input type="text" name="jam_buka_weekend" class="form-control" placeholder="08:00 - 18:00" value="{{ old('jam_buka_weekend') }}">
                @error('jam_buka_weekend')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Harga Tiket Weekday</label>
                <input type="number" name="harga_tiket_weekday" class="form-control" value="{{ old('harga_tiket_weekday', 0) }}">
                @error('harga_tiket_weekday')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Harga Tiket Weekend</label>
                <input type="number" name="harga_tiket_weekend" class="form-control" value="{{ old('harga_tiket_weekend', 0) }}">
                @error('harga_tiket_weekend')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="mb-3">
    <label>Gambar Destinasi</label>
    <input type="file" name="foto" class="form-control">

    @error('foto')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label>Foto Slider</label>
    <input type="file" name="fotos[]" class="form-control" multiple>

    <small class="text-muted">Bisa pilih lebih dari 1 gambar</small>
</div>

       <div class="mb-3">
    <label>Kategori</label>
    <select name="id_kategori" class="form-control" required>
        <option value="">-- Pilih Kategori --</option>
        @foreach($kategori as $k)
    <option value="{{ $k->id_kategori }}"
        {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
        {{ $k->nama_kategori }}
    </option>
@endforeach
    </select>

    @error('id_kategori')
        <div class="text-danger">{{ $message }}</div>
    @enderror
</div>

        <button type="submit" class="btn btn-primary w-100">Simpan Destinasi</button>
    </form>
</div>
@endsection