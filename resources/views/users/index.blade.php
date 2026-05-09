@extends('layouts.app')

@section('content')
<div class="container py-4">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Header: Judul + Tombol Tambah User --}}
    <div class="d-flex justify-content-between align-items-start mb-3">
        <div>
            <h4 class="fw-bold mb-0" style="font-size: 1.4rem;">Kelola User</h4>
            <p class="text-muted mb-0" style="font-size: 0.85rem;">Manage dan monitoring semua user dalam sistem</p>
        </div>
        {{-- ✅ Ganti btn-outline-secondary jadi btn-primary --}}
        <a href="{{ route('superadmin.users.create') }}"
           class="btn btn-sm btn-primary d-flex align-items-center gap-1"
           style="font-size: 0.82rem; border-radius: 8px;">
            <i class="bi bi-person-plus"></i> Tambah User
        </a>
    </div>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4 col-6">
            <div class="card border-0 p-3" style="background: #FFFFFF; border-radius: 12px;">
                <p class="text-muted mb-1" style="font-size: 0.78rem;">Total User</p>
                <h3 class="fw-bold mb-0" style="font-size: 1.6rem;">{{ $totalUser }}</h3>
            </div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card border-0 p-3" style="background: #FFFFFF; border-radius: 12px;">
                <p class="text-muted mb-1" style="font-size: 0.78rem;">User Aktif</p>
                <h3 class="fw-bold mb-0" style="font-size: 1.6rem;">{{ $userAktif }}</h3>
            </div>
        </div>
    </div>

    {{-- ✅ "Daftar User" dipindah keluar dari card --}}
    <h6 class="fw-semibold mb-2">Daftar User</h6>

    <!-- Table -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
        <div class="table-responsive">
            <table class="table align-middle mb-0" style="font-size: 0.85rem;">
                <thead>
                    <tr style="background: #fafafa;">
                        <th class="fw-semibold ps-3" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">USER</th>
                        <th class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">EMAIL</th>
                        <th class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">ROLE</th>
                        <th class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">STATUS</th>
                        <th class="fw-semibold" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">TANGGAL GABUNG</th>
                        <th class="fw-semibold text-center" style="font-size: 0.75rem; letter-spacing: 0.04em; color: #000;">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr style="border-bottom: 1px solid #f0f0f0;">

                        <!-- USER -->
                        <td class="ps-3">
                            <div class="d-flex align-items-center gap-2">
                                <div style="
                                    width: 34px;
                                    height: 34px;
                                    border-radius: 50%;
                                    background: #e8e8e8;
                                    color: #000000;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    font-size: 0.8rem;
                                    font-weight: 600;
                                    flex-shrink: 0;
                                ">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <span class="fw-semibold" style="font-size: 0.85rem;">{{ $user->name }}</span>
                            </div>
                        </td>

                        <!-- EMAIL -->
                        <td style="color: #000000;">{{ $user->email }}</td>

                        <!-- ROLE -->
                        <td>
                            @php
                                $role  = filled($user->role) ? strtolower($user->role) : 'user';
                                $color = match($role) {
                                    'admin'      => ['bg' => '#fff0f0', 'text' => '#c0392b'],
                                    'editor'     => ['bg' => '#fff8e1', 'text' => '#b8860b'],
                                    'superadmin' => ['bg' => '#f3e8ff', 'text' => '#7c3aed'],
                                    default      => ['bg' => '#eef2ff', 'text' => '#3b4fd8'],
                                };
                            @endphp
                            <span style="
                                background: {{ $color['bg'] }};
                                color: {{ $color['text'] }};
                                padding: 3px 10px;
                                border-radius: 20px;
                                font-size: 0.75rem;
                                font-weight: 500;
                            ">{{ ucfirst($role) }}</span>
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($user->status === 'active')
                                <span style="
                                    background: #eafaf1;
                                    color: #1e8449;
                                    padding: 3px 10px;
                                    border-radius: 20px;
                                    font-size: 0.75rem;
                                    font-weight: 500;
                                ">Active</span>
                            @else
                                <span style="
                                    background: #f2f2f2;
                                    color: #888;
                                    padding: 3px 10px;
                                    border-radius: 20px;
                                    font-size: 0.75rem;
                                    font-weight: 500;
                                ">Inactive</span>
                            @endif
                        </td>

                        <!-- TANGGAL GABUNG -->
                        <td style="color: #000000;">
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>

                        <!-- AKSI -->
                        <td class="text-center">
                            <form action="{{ route('superadmin.users.destroy', $user->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                {{-- ✅ Border merah, ikon merah --}}
                                <button type="submit"
                                        class="btn btn-sm"
                                        style="border: 1.5px solid #DD2A2A; border-radius: 8px; padding: 4px 8px; background: transparent;"
                                        onclick="return confirm('Hapus user ini?')">
                                    <i class="bi bi-trash" style="font-size: 0.8rem; color: #DD2A2A;"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted" style="font-size: 0.85rem;">
                            Tidak ada data user
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection