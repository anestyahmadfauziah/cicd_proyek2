@extends('layouts.app')

@section('content')
<div class="container py-4">

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
                                    {{ strtoupper(substr($user->name,0,1)) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>
                        </td>

                        <!-- EMAIL -->
                        <td>{{ $user->email }}</td>

                        <!-- ROLE -->
                        <td>
                            @php
                                $role = filled($user->role) ? strtolower($user->role) : 'user';

                                $color = match($role) {
                                    'admin' => 'danger',
                                    'editor' => 'warning',
                                    default => 'primary'
                                };
                            @endphp

                            <span class="badge bg-{{ $color }}">
                                {{ ucfirst($role) }}
                            </span>
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success">
                                    Active
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        <!-- TANGGAL GABUNG -->
                        <td>
                            {{ $user->tanggal_gabung
                                ? \Carbon\Carbon::parse($user->tanggal_gabung)->format('d M Y')
                                : '-' }}
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