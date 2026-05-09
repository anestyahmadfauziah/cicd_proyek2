@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="fw-bold mb-0" style="font-size: 1.4rem;">Rekomendasi Wisata</h4>
        <p class="text-muted mb-0" style="font-size: 0.85rem;">Kelola Rekomendasi semua destinasi wisata CIAYUMAJAKUNING</p>
    </div>
    <a href="{{ route($prefix.'.rekomendasi.create') }}" class="btn btn-primary btn-sm px-3" style="font-size: 0.82rem; border-radius: 8px;">
        <i class="bi bi-plus-lg"></i> Tambah Rekomendasi
    </a>
</div>

<!-- Info Box -->
<div class="p-3 mb-4 border rounded-3 bg-white" style="font-size: 0.85rem; color: #555;">
    <i class="bi bi-info-circle me-1"></i> Destinasi ini akan ditampilkan di halaman utama aplikasi
</div>

<!-- List -->
<div class="d-flex flex-column gap-3">

    @forelse($rekomendasi as $item)
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body d-flex align-items-center gap-3 py-3 px-3">

            {{-- Gambar --}}
            <img src="{{ asset($item->destinasi->foto ?? '') }}"
            style="width: 70px; height: 70px; object-fit: cover; border-radius: 10px; flex-shrink: 0;"
            onerror="this.src='https://via.placeholder.com/70'">

            {{-- Info --}}
            <div class="flex-grow-1">
                <p class="fw-bold mb-0" style="font-size: 0.9rem;">{{ $item->destinasi->nama ?? '-' }}</p>
                <p class="text-muted mb-0" style="font-size: 0.78rem;">
                    <i class="bi bi-geo-alt-fill text-danger" style="font-size: 0.7rem;"></i>
                    {{ $item->destinasi->lokasi ?? $item->destinasi->kabupaten ?? '-' }}
                </p>
            </div>

            {{-- Aksi --}}
            <div class="d-flex gap-2">
                <a href="{{ route($prefix.'.rekomendasi.edit', $item->id) }}"
                   class="btn btn-sm"
                   style="border: 1.5px solid #2563eb; border-radius: 8px; padding: 4px 8px; background: transparent;">
                    <i class="bi bi-pencil" style="font-size: 0.8rem; color: #2563eb;"></i>
                </a>
                <form action="{{ route($prefix.'.rekomendasi.destroy', $item->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-sm"
                            style="border: 1.5px solid #DD2A2A; border-radius: 8px; padding: 4px 8px; background: transparent;"
                            onclick="return confirm('Hapus rekomendasi ini?')">
                        <i class="bi bi-trash" style="font-size: 0.8rem; color: #DD2A2A;"></i>
                    </button>
                </form>
            </div>

        </div>
    </div>
    @empty
    <div class="text-center text-muted py-4" style="font-size: 0.85rem;">
        Belum ada rekomendasi destinasi
    </div>
    @endforelse

</div>

@endsection