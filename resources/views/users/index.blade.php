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

    <h2 class="fw-bold">Kelola User</h2>
    <p class="text-muted">Manage dan monitoring semua user dalam sistem</p>

    <!-- Statistik -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">Total User</h6>
                <h2>{{ $totalUser }}</h2>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm p-3">
                <h6 class="text-muted">User Aktif</h6>
                <h2>{{ $userAktif }}</h2>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="card shadow-sm">

        {{-- ✅ Tombol Tambah User di atas tabel --}}
        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
            <h6 class="mb-0 fw-semibold">Daftar User</h6>
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-person-plus"></i> Tambah User
            </a>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>USER</th>
                        <th>EMAIL</th>
                        <th>ROLE</th>
                        <th>STATUS</th>
                        <th>TANGGAL GABUNG</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                    <tr>

                        <!-- USER -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle me-3">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <strong>{{ $user->name }}</strong>
                            </div>
                        </td>

                        <!-- EMAIL -->
                        <td>{{ $user->email }}</td>

                        <!-- ROLE -->
                        <td>
                            @php
                                $role  = filled($user->role) ? strtolower($user->role) : 'user';
                                $color = match($role) {
                                    'admin'  => 'danger',
                                    'editor' => 'warning',
                                    default  => 'primary'
                                };
                            @endphp
                            <span class="badge bg-{{ $color }}">{{ ucfirst($role) }}</span>
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">Inactive</span>
                            @endif
                        </td>

                        <!-- TANGGAL GABUNG -->
                        <td>
                            {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                        </td>

                        <!-- AKSI -->
                        <td class="text-center">
                            <form action="{{ route('superadmin.users.destroy', $user->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn btn-link text-danger p-0"
                                        onclick="return confirm('Hapus user ini?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
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