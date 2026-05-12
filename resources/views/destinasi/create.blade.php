@extends('layouts.app')

@section('content')
@php
    $prefix = auth('superadmin')->check() ? 'superadmin' : 'admin';
@endphp

<div class="edit-destinasi-wrapper">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Tambah Destinasi Wisata</h2>
            <p>Isi informasi destinasi wisata baru</p>
        </div>
    </div>

    {{-- ALERT SUCCESS --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- ALERT ERROR --}}
    @if($errors->any())
    <div style="background:#fef2f2; border:1px solid #fca5a5; color:#991b1b; border-radius:10px; padding:.75rem 1.1rem; font-size:.875rem; margin-bottom:1.25rem; display:flex; align-items:flex-start; gap:.5rem;">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        <ul style="margin:0; padding-left:1rem;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route($prefix.'.destinasi.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- INFORMASI DASAR --}}
        <div class="card-section">
            <div class="card-section-title">Informasi Dasar</div>

            <div class="mb-3">
                <label class="form-label-custom">Nama Destinasi</label>
                <input type="text" name="nama" class="form-control-custom"
                       value="{{ old('nama') }}" required placeholder="Contoh: Pantai Indramayu">
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label-custom">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control-custom"
                           value="{{ old('lokasi') }}" required placeholder="Contoh: Indramayu">
                </div>
                <div>
                    <label class="form-label-custom">Kategori</label>
                    <select name="id_kategori" class="form-control-custom" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ old('id_kategori') == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label-custom">Deskripsi</label>
                <textarea name="deskripsi" class="form-control-custom" required
                          placeholder="Deskripsikan destinasi wisata ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="mt-3">
                <label class="form-label-custom">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control-custom" required
                          placeholder="Masukkan alamat lengkap destinasi...">{{ old('alamat_lengkap') }}</textarea>
            </div>
        </div>

        {{-- JAM & HARGA --}}
        <div class="card-section">
            <div class="card-section-title">Jam Operasional & Harga Tiket</div>

            <div class="grid-2">
                <div>
                    <label class="form-label-custom">Jam Buka Weekday</label>
                    <input type="text" name="jam_buka_weekday" class="form-control-custom"
                           value="{{ old('jam_buka_weekday') }}" placeholder="08:00 - 17:00">
                </div>
                <div>
                    <label class="form-label-custom">Jam Buka Weekend</label>
                    <input type="text" name="jam_buka_weekend" class="form-control-custom"
                           value="{{ old('jam_buka_weekend') }}" placeholder="08:00 - 18:00">
                </div>
                <div>
                    <label class="form-label-custom">Harga Tiket Weekday</label>
                    <input type="number" name="harga_tiket_weekday" class="form-control-custom"
                           value="{{ old('harga_tiket_weekday', 0) }}">
                </div>
                <div>
                    <label class="form-label-custom">Harga Tiket Weekend</label>
                    <input type="number" name="harga_tiket_weekend" class="form-control-custom"
                           value="{{ old('harga_tiket_weekend', 0) }}">
                </div>
            </div>
        </div>

        {{-- MEDIA --}}
        <div class="card-section">
            <div class="card-section-title">Media</div>

            <div class="grid-2">
                {{-- FOTO COVER --}}
                <div>
                    <label class="form-label-custom">Gambar Cover <span style="color:#ef4444;">*</span></label>
                    <div class="file-upload-box">
                        <label for="foto-cover">
                            <div class="file-upload-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4 4 4 4-8 4 8"/></svg>
                            </div>
                            <span class="file-upload-text" id="foto-cover-label">Pilih Gambar Cover</span>
                            <span class="file-upload-sub">JPG, PNG, WEBP</span>
                            <input type="file" id="foto-cover" name="foto" accept="image/*" required
                                   onchange="updateLabel(this, 'foto-cover-label')">
                        </label>
                    </div>
                </div>

                {{-- FOTO SLIDER --}}
                <div>
                    <label class="form-label-custom">Foto Slider</label>
                    <div class="file-upload-box">
                        <label for="foto-slider">
                            <div class="file-upload-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </div>
                            <span class="file-upload-text" id="foto-slider-label">Pilih Foto Slider</span>
                            <span class="file-upload-sub">Bisa lebih dari 1 gambar</span>
                            <input type="file" id="foto-slider" name="fotos[]" accept="image/*" multiple
                                   onchange="updateLabel(this, 'foto-slider-label')">
                        </label>
                    </div>
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn-submit">
            Simpan Destinasi
        </button>

    </form>
</div>

<script>
function updateLabel(input, labelId) {
    const el = document.getElementById(labelId);
    if (!el) return;
    if (input.files.length === 1) {
        el.textContent = input.files[0].name;
    } else if (input.files.length > 1) {
        el.textContent = input.files.length + ' file dipilih';
    }
}
</script>

@endsection