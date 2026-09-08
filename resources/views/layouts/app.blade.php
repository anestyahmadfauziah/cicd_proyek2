<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS inti dashboard (dipakai di semua halaman) -->
    <link href="{{ asset('css/admin/layout.css') }}" rel="stylesheet">

    <!-- CSS khusus per halaman: setiap view push file CSS-nya sendiri di sini -->
    @stack('styles')
</head>
<body>

@if(Auth::check())

<div class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo">
    </div>

    <!-- Menu -->
    <div class="sidebar-menu">

        <div class="sidebar-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i> Halaman Utama
        </a>

        {{-- Destinasi --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.destinasi.index') }}">
            <i class="bi bi-geo-alt"></i> Destinasi Wisata
        </a>
        @elseif(auth()->guard('web')->check())
        <a href="{{ route('admin.destinasi.index') }}">
            <i class="bi bi-geo-alt"></i> Destinasi Wisata
        </a>
        @endif

        {{-- Rekomendasi (superadmin) --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.rekomendasi.index') }}">
            <i class="bi bi-star"></i> Rekomendasi
        </a>
        @endif

        {{-- Users (superadmin) --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.users.index') }}">
            <i class="bi bi-people"></i> Kelola Users
        </a>
        @endif

        {{-- Transaksi --}}
        @if(auth()->guard('superadmin')->check())
        <div class="sidebar-label" style="margin-top:10px;">Keuangan</div>
        <a href="{{ route('superadmin.transaksi') }}">
            <i class="bi bi-cart3"></i> Transaksi
        </a>
        @elseif(auth()->guard('web')->check())
        <div class="sidebar-label" style="margin-top:10px;">Keuangan</div>
        <a href="{{ route('admin.transaksi.index') }}">
            <i class="bi bi-cart3"></i> Transaksi
        </a>
        @endif

    </div>

    <!-- Bottom -->
    <div class="sidebar-bottom">
        <hr class="sidebar-divider">

        {{-- User chip --}}
        @php
            $sidebarUser = auth()->guard('superadmin')->check()
                ? auth()->guard('superadmin')->user()
                : auth()->guard('web')->user();

            $displayName = trim(($sidebarUser->first_name ?? '') . ' ' . ($sidebarUser->last_name ?? ''));
            if (!$displayName) $displayName = $sidebarUser->name ?? 'User';
        @endphp

        @if($sidebarUser)
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr($displayName, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ $displayName }}</div>
                <div class="user-role">
                    @if(auth()->guard('superadmin')->check()) Super Admin
                    @else Admin @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Pengaturan --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.pengaturan') }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
        @elseif(auth()->guard('web')->check())
        <a href="{{ route('admin.pengaturan') }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
        @endif

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin-top:6px;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>

    </div>

</div>

@endif

<div class="content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

@stack('scripts')

</body>
</html>