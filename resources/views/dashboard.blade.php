{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

<h4 class="fw-bold">
    @if(Auth::guard('superadmin')->check())
        Selamat Datang, {{ Auth::guard('superadmin')->user()->first_name ?? Auth::guard('superadmin')->user()->name ?? 'Superadmin' }}!
    @else
        Selamat Datang, {{ Auth::user()->first_name ?? 'Admin' }}!
    @endif
</h4>
<p class="text-muted">Dashboard Sistem Informasi Destinasi Wisata CIAYUMAJAKUNING!</p>

@php
    // Helper inline untuk menentukan route berdasarkan guard
    function routeByRole($type) {
        if(Auth::guard('superadmin')->check()) {
            return match($type) {
                'destinasi' => route('superadmin.destinasi.index'),
                'transaksi' => route('superadmin.transaksi'),
                'users' => route('superadmin.users.index'),
                default => '#',
            };
        } else {
            return match($type) {
                'destinasi' => route('admin.destinasi.index'),
                'transaksi' => route('admin.transaksi.index'),
                default => '#',
            };
        }
    }
@endphp

<div class="row mt-4">

    <!-- Destinasi -->
    <div class="col-md-3">
        <a href="{{ routeByRole('destinasi') }}" class="text-decoration-none text-dark">
            <div class="card card-stat shadow-sm card-click">
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Total Destinasi Wisata</small>
                        <h4 class="fw-bold text-center w-100">{{ $totalDestinasi ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-geo-alt fs-3 text-primary"></i>
                </div>
            </div>
        </a>
    </div>

    <!-- Users (Super Admin Only) -->
    @if(Auth::guard('superadmin')->check())
    <div class="col-md-3">
        <a href="{{ routeByRole('users') }}" class="text-decoration-none text-dark">
            <div class="card card-stat shadow-sm card-click">
                 <div class="d-flex justify-content-between">
                    <div>
                        <small>Total Pengguna</small>
                        <h4 class="fw-bold text-center w-100">{{ $totalUsers ?? 0 }}</h4>
                    </div>
                    <i class="bi bi-people fs-3 text-success"></i>
                </div>
            </div>
        </a>
    </div>
    @endif

    <!-- Transaksi -->
<div class="col-md-3">
    <a href="{{ routeByRole('transaksi') }}" class="text-decoration-none text-dark">
        <div class="card card-stat shadow-sm card-click">
            <div class="d-flex justify-content-between">
                <div>
                   <small class="d-block text-center">Transaksi Bulan Ini</small>
                   <h4 class="fw-bold text-center w-100">
                       {{ $transaksiBulanIni ?? 0 }}
                   </h4>
                </div>
                <i class="bi bi-cart fs-3 text-danger"></i>
            </div>
        </div>
    </a>
</div>

<!-- Pendapatan -->
<div class="col-md-3">
    <a href="{{ routeByRole('transaksi') }}" class="text-decoration-none text-dark">
        <div class="card card-stat shadow-sm card-click">
            <div class="d-flex justify-content-between">
                <div>
                    <small>Pendapatan</small>
                    <h4 class="fw-bold text-center w-100">
                        Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                    </h4>
                </div>
                <i class="bi bi-currency-dollar fs-3 text-warning"></i>
            </div>
        </div>
    </a>
</div>


<div class="row mt-4">

    <!-- Aktivitas -->
    <div class="col-md-8">
        <div class="card p-4 shadow-sm">
            <h6 class="fw-bold mb-3">Aktivitas Terkini</h6>

            @forelse($activities as $item)
<a href="{{ routeByRole('destinasi') }}" class="text-decoration-none text-dark">

<div class="activity-item card-click d-flex justify-content-between align-items-center">
        
        <div>
            <strong>{{ $item->user_name }}</strong>
            <small class="text-muted d-block">{{ $item->activity }}</small>
        </div>

        <span class="badge bg-success">
            {{ $item->status }}
        </span>

</div>

</a>
@empty
<div class="text-muted">
    Belum ada aktivitas
</div>
@endforelse

        </div>
    </div>

    <!-- Kategori -->
    <div class="col-md-4">
        <div class="card p-4 shadow-sm">
            <h6 class="fw-bold mb-3">Kategori Wisata</h6>

            <ul class="list-unstyled">
    @php
        $kategoriRoutes = ['Pantai', 'Gunung & Alam', 'Budaya & Sejarah', 'Curug', 'Taman Air'];

        $icons = [
    'Pantai' => 'bi-sun text-warning',
    'Gunung & Alam' => 'bi-tree text-success',
    'Budaya & Sejarah' => 'bi-bank text-warning',
    'Curug' => 'bi-water text-info',
    'Taman Air' => 'bi-droplet-fill text-primary'
];
    @endphp

    @foreach($kategoriRoutes as $kategori)
        <li>
            <a href="{{ routeByRole('destinasi') }}" class="text-decoration-none text-dark">
                <i class="bi {{ $icons[$kategori] }} me-2"></i> {{ $kategori }}
            </a>
        </li>
    @endforeach
</ul>

        </div>
    </div>

</div>

@endsection