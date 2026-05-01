@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center">
    <div>
        <h3 class="fw-bold">Rekomendasi Wisata</h3>
        <p class="text-muted mb-0">Kelola Rekomendasi semua destinasi wisata CIAYUMAJAKUNING</p>
    </div>

    <div class="d-flex align-items-center">
        <img src="https://i.pravatar.cc/40" class="rounded-circle me-2">
        <div>
            <strong>Rizal Jamali</strong><br>
            <small class="text-muted">Super Admin</small>
        </div>
    </div>
</div>

<!-- Info Box -->
<div class="info-box mt-4 p-4 d-flex justify-content-between align-items-center">
    <span>Destinasi ini akan ditampilkan di halaman utama aplikasi</span>
    <a href="#" class="btn btn-primary rounded-3 px-4">
        <i class="bi bi-plus-lg"></i> Tambah Rekomendasi
    </a>
</div>

<!-- List -->
<div class="mt-4">

    <div class="rekom-card active-card">
        <div class="d-flex">
            <div class="rekom-img"></div>

            <div class="ms-4">
                <h5 class="fw-bold">Pantai Pangandaran</h5>

                <p class="text-muted mb-1">
                    <i class="bi bi-geo-alt"></i> Pangandaran, Jawa Barat
                </p>

                <p class="mb-2">
                    <i class="bi bi-star-fill text-warning"></i>
                    4.8 <span class="text-muted">(1234 ulasan)</span>
                </p>

                <a href="#" class="text-danger text-decoration-none">
                    <i class="bi bi-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>

</div>

@endsection