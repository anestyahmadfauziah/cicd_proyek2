@extends('layouts.app')

@section('content')

<div class="edit-destinasi-wrapper">

    {{-- HEADER --}}
    <div class="page-header">
        <div>
            <h2>Edit Destinasi Wisata</h2>
            <p>Perbarui informasi destinasi wisata</p>
        </div>
        @php $routePrefix = request()->is('superadmin/*') ? 'superadmin' : 'admin'; @endphp
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="alert-success-custom">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route($routePrefix . '.destinasi.update', $destinasi->id_destinasi) }}"
          method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- INFORMASI DASAR --}}
        <div class="card-section">
            <div class="card-section-title">Informasi Dasar</div>

            <div class="mb-3">
                <label class="form-label-custom">Nama Destinasi</label>
                <input type="text" name="nama" class="form-control-custom"
                       value="{{ old('nama', $destinasi->nama) }}" required>
            </div>

            <div class="grid-2">
                <div>
                    <label class="form-label-custom">Lokasi</label>
                    <input type="text" name="lokasi" class="form-control-custom"
                           value="{{ old('lokasi', $destinasi->lokasi) }}" required>
                </div>
                <div>
                    <label class="form-label-custom">Kategori</label>
                    <select name="id_kategori" class="form-control-custom" required>
                        @foreach($kategori as $k)
                            <option value="{{ $k->id_kategori }}"
                                {{ old('id_kategori', $destinasi->id_kategori) == $k->id_kategori ? 'selected' : '' }}>
                                {{ $k->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3">
                <label class="form-label-custom">Deskripsi</label>
                <textarea name="deskripsi" class="form-control-custom" required>{{ old('deskripsi', $destinasi->deskripsi) }}</textarea>
            </div>

            <div class="mt-3">
                <label class="form-label-custom">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control-custom" required>{{ old('alamat_lengkap', $destinasi->alamat_lengkap) }}</textarea>
            </div>
        </div>

        {{-- JAM & HARGA --}}
        <div class="card-section">
            <div class="card-section-title">Jam Operasional & Harga Tiket</div>

            <div class="grid-2">
                <div>
                    <label class="form-label-custom">Jam Buka Weekday</label>
                    <input type="text" name="jam_buka_weekday" class="form-control-custom"
                           value="{{ old('jam_buka_weekday', $destinasi->weekday) }}"
                           placeholder="09:00 - 17:00">
                </div>
                <div>
                    <label class="form-label-custom">Jam Buka Weekend</label>
                    <input type="text" name="jam_buka_weekend" class="form-control-custom"
                           value="{{ old('jam_buka_weekend', $destinasi->weekend) }}"
                           placeholder="09:00 - 16:00">
                </div>
                <div>
                    <label class="form-label-custom">Harga Tiket Weekday</label>
                    <input type="number" name="harga_tiket_weekday" class="form-control-custom"
                           value="{{ old('harga_tiket_weekday', $destinasi->harga_tiket_weekday) }}">
                </div>
                <div>
                    <label class="form-label-custom">Harga Tiket Weekend</label>
                    <input type="number" name="harga_tiket_weekend" class="form-control-custom"
                           value="{{ old('harga_tiket_weekend', $destinasi->harga_tiket_weekend) }}">
                </div>
            </div>
        </div>

        {{-- MEDIA --}}
        <div class="card-section">
            <div class="card-section-title">Media</div>

            <div class="grid-2">
                {{-- FOTO COVER --}}
                <div>
                    <label class="form-label-custom">Gambar Cover</label>
                    <div class="file-upload-box">
                        <label for="foto-cover">
                            <div class="file-upload-icon">
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4-4 4 4 4-8 4 8"/></svg>
                            </div>
                            <span class="file-upload-text" id="foto-cover-label">Pilih Gambar Cover</span>
                            <span class="file-upload-sub">JPG, PNG, WEBP</span>
                            <input type="file" id="foto-cover" name="foto" accept="image/*"
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

            {{-- VIDEO --}}
            <div class="mt-3">
                <label class="form-label-custom">Upload Video</label>
                <div class="file-upload-box">
                    <label for="upload-video">
                        <div class="file-upload-icon">
                            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.724v6.552a1 1 0 01-1.447.894L15 14M4 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H6a2 2 0 01-2-2V8z"/></svg>
                        </div>
                        <span class="file-upload-text" id="video-label">Pilih Video</span>
                        <span class="file-upload-sub">MP4 / MOV / AVI · maks. 500MB</span>
                        <input type="file" id="upload-video" name="video" accept="video/*"
                               onchange="updateLabel(this, 'video-label')">
                    </label>
                </div>
            </div>
        </div>

        {{-- SUBMIT --}}
        <button type="submit" class="btn-submit">
            Update Destinasi
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