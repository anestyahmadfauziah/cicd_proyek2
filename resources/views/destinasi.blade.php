@extends('layouts.app')

@section('content')

<!-- HEADER -->
<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <h3 class="fw-bold">Destinasi Wisata</h3>
        <small class="text-muted">
            Kelola dan pantau semua destinasi wisata CIAYUMAJAKUNING
        </small>
    </div>

    <!-- Profile kanan atas -->
    <div class="d-flex align-items-center">
        <img src="https://i.pravatar.cc/40"
             class="rounded-circle me-2">
        <div>
            <div class="fw-semibold">Rizal Jamali</div>
            <small class="text-muted">Super Admin</small>
        </div>
    </div>
</div>

<!-- SEARCH + BUTTON -->
<form action="{{ route($prefix . '.destinasi.index') }}" method="GET" class="d-flex justify-content-center align-items-center mb-5 gap-3">

    <div style="width: 500px;">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-0">
                <i class="bi bi-search"></i>
            </span>
            <input type="text"
                   name="keyword"
                   value="{{ $keyword ?? '' }}"
                   class="form-control border-0 bg-light"
                   placeholder="Cari destinasi...">
        </div>
    </div>

    <button type="submit" class="btn btn-primary px-4 shadow-sm">
        <i class="bi bi-search"></i> Cari
    </button>

    <a href="{{ route($prefix . '.destinasi.create') }}" class="btn btn-success px-4 shadow-sm">
        <i class="bi bi-plus-lg"></i> Tambah Destinasi
    </a>

</form>

<!-- GRID DESTINASI -->
<div class="row">

@forelse($destinasi as $d)
<div class="col-md-3 mb-4">

    <div class="card border-0 shadow-sm destinasi-card h-100"
         onclick="window.location='{{ route($prefix . '.destinasi.detail', $d->id) }}'">

        <img src="{{ $d->foto ? asset('storage/' . $d->foto) : 'https://via.placeholder.com/300x200' }}"
             class="card-img-top rounded-top">

        <div class="card-body">

            <h6 class="fw-bold mb-1">{{ $d->nama }}</h6>

            <small class="text-muted">
                <i class="bi bi-geo-alt"></i> {{ $d->lokasi }}
            </small>

            <div class="mt-2">
                ⭐ <span class="fw-semibold">4.8</span>
                <small class="text-muted">(1,234 ulasan)</small>
            </div>

            <div class="mt-3 d-flex justify-content-between align-items-center">

                <div>
                    <small class="text-muted">Harga Tiket</small><br>
                    <span class="text-primary fw-bold">
                        Rp {{ number_format($d->harga_tiket_weekday,0,',','.') }}
                    </span>
                </div>

                <div>
                    <i class="bi bi-pencil-square text-primary me-2"
                       onclick="event.stopPropagation(); window.location='{{ route($prefix . '.destinasi.edit', $d->id) }}'"></i>

                    <i class="bi bi-trash text-danger"
                       onclick="event.stopPropagation(); if(confirm('Hapus destinasi {{ $d->nama }}?')) { window.location='{{ route($prefix . '.destinasi.destroy', $d->id) }}' }"></i>
                </div>

            </div>

        </div>

    </div>

</div>
@empty
<div class="col-12 text-center">
    <p class="text-muted">Destinasi tidak ditemukan.</p>
</div>
@endforelse

</div>

<!-- PAGINATION -->
<div class="d-flex justify-content-center mt-4">
    {{ $destinasi->withQueryString()->links() }}
</div>

@endsection