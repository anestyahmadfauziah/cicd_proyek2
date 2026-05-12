{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')

@php
function routeByRole($type) {
    if(Auth::guard('superadmin')->check()) {
        return match($type) {
            'destinasi' => route('superadmin.destinasi.index'),
            'transaksi' => route('superadmin.transaksi'),
            'users'     => route('superadmin.users.index'),
            default     => '#',
        };
    } else {
        return match($type) {
            'destinasi' => route('admin.destinasi.index'),
            'transaksi' => route('admin.transaksi.index'),
            default     => '#',
        };
    }
}
@endphp

{{-- HEADER --}}
<div style="margin-bottom:1.5rem;">
    <h2 style="font-size:1.4rem; font-weight:700; color:var(--text-main); margin:0;">
        Selamat Datang,
        @if(Auth::guard('superadmin')->check())
            {{ Auth::guard('superadmin')->user()->first_name ?? 'Superadmin' }}!
        @else
            {{ Auth::user()->first_name ?? 'Admin' }}!
        @endif
    </h2>
    <p style="font-size:.83rem; color:var(--text-muted); margin:.2rem 0 0;">Dashboard Sistem Informasi Destinasi Wisata CIAYUMAJAKUNING</p>
</div>

{{-- STAT CARDS --}}
<div style="display:grid; grid-template-columns:repeat(4,1fr); gap:.9rem; margin-bottom:1.25rem;">

    {{-- Destinasi --}}
    <a href="{{ routeByRole('destinasi') }}" style="text-decoration:none;">
        <div class="card-stat" style="gap:12px; padding:16px 18px;">
            <div class="stat-icon icon-blue" style="width:42px; height:42px; border-radius:10px; font-size:18px;">
                <i class="bi bi-geo-alt"></i>
            </div>
            <div class="stat-text">
                <div class="stat-label">Total Destinasi</div>
                <div class="stat-value" style="font-size:22px;">{{ $totalDestinasi ?? 0 }}</div>
            </div>
        </div>
    </a>

    {{-- Users (superadmin only) --}}
    @if(Auth::guard('superadmin')->check())
    <a href="{{ routeByRole('users') }}" style="text-decoration:none;">
        <div class="card-stat" style="gap:12px; padding:16px 18px;">
            <div class="stat-icon icon-teal" style="width:42px; height:42px; border-radius:10px; font-size:18px;">
                <i class="bi bi-people"></i>
            </div>
            <div class="stat-text">
                <div class="stat-label">Total Pengguna</div>
                <div class="stat-value" style="font-size:22px;">{{ $totalUsers ?? 0 }}</div>
            </div>
        </div>
    </a>
    @endif

    {{-- Transaksi --}}
    <a href="{{ routeByRole('transaksi') }}" style="text-decoration:none;">
        <div class="card-stat" style="gap:12px; padding:16px 18px;">
            <div class="stat-icon icon-orange" style="width:42px; height:42px; border-radius:10px; font-size:18px;">
                <i class="bi bi-cart3"></i>
            </div>
            <div class="stat-text">
                <div class="stat-label">Transaksi Bulan Ini</div>
                <div class="stat-value" style="font-size:22px;">{{ $transaksiBulanIni ?? 0 }}</div>
            </div>
        </div>
    </a>

    {{-- Pendapatan --}}
    <a href="{{ routeByRole('transaksi') }}" style="text-decoration:none;">
        <div class="card-stat" style="gap:12px; padding:16px 18px;">
            <div class="stat-icon icon-purple" style="width:42px; height:42px; border-radius:10px; font-size:18px;">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-text">
                <div class="stat-label">Pendapatan</div>
                <div class="stat-value" style="font-size:16px; font-weight:700;">
                    Rp {{ number_format($pendapatanBulanIni ?? 0, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </a>

</div>

{{-- BOTTOM ROW --}}
<div style="display:grid; grid-template-columns:1fr 300px; gap:.9rem;">

    {{-- AKTIVITAS TERKINI --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h6>Aktivitas Terkini</h6>
            <span class="header-badge">{{ count($activities) }} aktivitas</span>
        </div>
        <div class="card-panel-body" style="padding:0;">
            @forelse($activities as $item)
            <a href="{{ routeByRole('destinasi') }}" style="text-decoration:none; display:block;">
                <div style="display:flex; justify-content:space-between; align-items:center; padding:10px 20px; border-bottom:1px solid #f0f6ff; gap:12px;"
                     onmouseover="this.style.background='#f8fbff'" onmouseout="this.style.background=''">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:7px; height:7px; border-radius:50%; background:var(--blue-bright); flex-shrink:0;"></div>
                        <div>
                            <div style="font-size:.83rem; font-weight:600; color:var(--text-main);">{{ $item->user_name }}</div>
                            <div style="font-size:.75rem; color:var(--text-muted); margin-top:1px;">{{ $item->activity }}</div>
                        </div>
                    </div>
                    <span style="background:#EAF3DE; color:#3B6D11; font-size:11px; font-weight:500; padding:3px 9px; border-radius:20px; white-space:nowrap; flex-shrink:0;">
                        {{ $item->status }}
                    </span>
                </div>
            </a>
            @empty
            <div style="padding:2rem; text-align:center; color:var(--text-muted); font-size:13px;">
                <i class="bi bi-inbox" style="font-size:22px; display:block; margin-bottom:.4rem;"></i>
                Belum ada aktivitas
            </div>
            @endforelse
        </div>
    </div>

    {{-- KATEGORI WISATA --}}
    <div class="card-panel">
        <div class="card-panel-header">
            <h6>Kategori Wisata</h6>
        </div>
        <div class="card-panel-body">
            @php
            $kategoriList = [
                'Pantai'          => ['icon' => 'bi-sun',          'color' => '#ea8c3b', 'bg' => '#fff3e8'],
                'Gunung & Alam'   => ['icon' => 'bi-tree',         'color' => '#0d9488', 'bg' => '#e6f9f5'],
                'Budaya & Sejarah'=> ['icon' => 'bi-bank',         'color' => '#7c3aed', 'bg' => '#f0eaff'],
                'Curug'           => ['icon' => 'bi-water',        'color' => '#2e8de8', 'bg' => '#e8f3fd'],
                'Taman Air'       => ['icon' => 'bi-droplet-fill', 'color' => '#1a6bbf', 'bg' => '#dbeafe'],
            ];
            @endphp

            <div style="display:flex; flex-direction:column; gap:.5rem;">
                @foreach($kategoriList as $nama => $style)
                <a href="{{ routeByRole('destinasi') }}" style="text-decoration:none; display:flex; align-items:center; gap:.65rem; padding:.5rem .65rem; border-radius:8px; transition:background .15s;"
                   onmouseover="this.style.background='var(--bg-page)'" onmouseout="this.style.background=''">
                    <div style="width:30px; height:30px; border-radius:8px; background:{{ $style['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                        <i class="bi {{ $style['icon'] }}" style="color:{{ $style['color'] }}; font-size:14px;"></i>
                    </div>
                    <span style="font-size:.83rem; font-weight:500; color:var(--text-main);">{{ $nama }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

</div>

@endsection