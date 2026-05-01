@extends('layouts.app')

@section('content')
<div class="container">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold">Rekomendasi Wisata</h4>
            <small class="text-muted">
                Kelola Rekomendasi semua destinasi wisata CIAYUMAJAKUNING
            </small>
        </div>

        @if($prefix === 'superadmin')
        <a href="{{ route('superadmin.rekomendasi.create') }}" 
           class="btn btn-primary">
            + Tambah Rekomendasi
        </a>
        @endif
    </div>

    {{-- INFO BOX --}}
    <div class="alert alert-light border mb-4">
        Destinasi ini akan ditampilkan di halaman utama aplikasi
    </div>

    {{-- LIST REKOMENDASI --}}
    @forelse($rekomendasi as $r)
    <div class="card shadow-sm mb-3 border-0">
        <div class="card-body d-flex align-items-center">

            {{-- GAMBAR --}}
            <div style="width:100px;height:100px;overflow:hidden;border-radius:8px;">
                <img src="{{ $r->destinasi->foto }}" class="img-fluid h-100 w-100" style="object-fit:cover;">
            </div>

            {{-- INFO --}}
            <div class="ms-4 flex-grow-1">
                <h5 class="mb-1">{{ $r->destinasi->nama }}</h5>
                <small class="text-muted d-block">📍 {{ $r->destinasi->lokasi }}</small>
                <small class="text-warning">⭐ 4.8 (1234 ulasan)</small>
                <p class="mt-2 mb-0 text-muted">{{ $r->alasan }}</p>
            </div>

            {{-- ACTION --}}
            @if($prefix === 'superadmin')
            <div class="d-flex gap-2">
                <a href="{{ route('superadmin.rekomendasi.edit', $r->id) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('superadmin.rekomendasi.destroy', $r->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </div>
            @endif

        </div>
    </div>
    @empty
    <div class="alert alert-secondary">
        Belum ada rekomendasi.
    </div>
    @endforelse

</div>
@endsection