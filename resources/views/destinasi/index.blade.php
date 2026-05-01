{{-- resources/views/destinasi/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    // Prefix route: superadmin atau admin
    $prefix = $prefix ?? 'admin'; 
@endphp

<div class="container mt-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Destinasi Wisata</h2>
            <small class="text-muted">Kelola dan pantau semua destinasi wisata</small>
        </div>

        <a href="{{ route($prefix.'.destinasi.create') }}" 
           class="btn btn-primary rounded-3">
            + Tambah Destinasi
        </a>
    </div>

    <!-- SEARCH BAR -->
    <form method="GET" action="{{ route($prefix.'.destinasi.index') }}" class="mb-4">
        <div class="input-group">
            <input type="text"
                   name="keyword"
                   value="{{ $keyword ?? '' }}"
                   class="form-control rounded-start-3"
                   placeholder="Search nama atau lokasi destinasi...">
            <button class="btn btn-primary rounded-end-3">Cari</button>
        </div>
    </form>

    <!-- GRID DESTINASI -->
    <div class="row g-4">
        @forelse($destinasi as $item)
            <div class="col-lg-4 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 d-flex flex-column" style="height:470px;">

                    <!-- GAMBAR -->
                    @if($item->foto)
                        <img src="{{ $item->foto }}" class="card-img-top rounded-top-4" style="height:180px; object-fit:cover;">
                    @else
                        <img src="https://via.placeholder.com/300x200" class="card-img-top rounded-top-4">
                    @endif

                    <!-- BODY (SCROLLABLE) -->
                    <div class="card-body d-flex flex-column overflow-auto">

                        <!-- NAMA -->
                        <h6 class="fw-bold mb-1">{{ $item->nama }}</h6>

                        <!-- LOKASI -->
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-geo-alt"></i> {{ $item->lokasi }}
                        </small>

                        <!-- ALAMAT -->
                        <small class="text-muted d-block mb-2">
                            <i class="bi bi-house-door"></i> {{ $item->alamat_lengkap ?? '-' }}
                        </small>

                        <!-- DESKRIPSI -->
                    <p class="text-muted mb-3" style="font-size:0.85rem; white-space: pre-line;">
                        {{ $item->deskripsi ?? 'Belum ada deskripsi' }}
                    </p>

                        <!-- RATING -->
                        <div class="mb-3">
                            ⭐ 4.8 <small class="text-muted">(123 ulasan)</small>
                        </div>

                        <!-- HARGA -->
                        <div class="mb-2">
                            <small class="text-muted">Harga:</small><br>
                            <span class="fw-bold text-primary">
                                Weekday Rp {{ number_format($item->harga_tiket_weekday,0,',','.') }}
                            </span><br>
                            <span class="fw-bold text-success">
                                Weekend Rp {{ number_format($item->harga_tiket_weekend,0,',','.') }}
                            </span>
                        </div>

                        <!-- JAM -->
                        <div class="mb-2">
                            <small class="text-muted">Jam:</small><br>
                            <span class="fw-semibold">{{ $item->weekday }} | {{ $item->weekend }}</span>
                        </div>

                        <!-- ACTION BUTTON -->
                        <div class="mt-auto pt-3">
                            <div class="d-flex gap-2">
                                <a href="{{ route($prefix.'.destinasi.edit', $item->id_destinasi) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                <form action="{{ route($prefix.'.destinasi.destroy', $item->id_destinasi) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        @empty
            <div class="col-12 text-center">
                Belum ada data destinasi.
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div class="d-flex justify-content-center mt-4">
        {{ $destinasi->links() }}
    </div>

</div>
@endsection