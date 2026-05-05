@extends('layouts.app')

@section('content')
<div class="container py-4" style="max-width: 680px;">

    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-user-card">
        <form action="{{ route('superadmin.users.store') }}" method="POST">
            @csrf

            <p class="form-section-label">Informasi akun</p>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size: 13px;">Nama lengkap</label>
                    <input type="text" name="name"
                           class="form-control @error('name') is-invalid @enderror"
                           placeholder="Masukkan nama lengkap"
                           value="{{ old('name') }}" required>
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-medium" style="font-size: 13px;">Username</label>
                    <input type="text" name="username"
                           class="form-control @error('username') is-invalid @enderror"
                           placeholder="Masukkan username"
                           value="{{ old('username') }}" required>
                    @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium" style="font-size: 13px;">Email</label>
                <input type="email" name="email"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="contoh@email.com"
                       value="{{ old('email') }}" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <hr class="my-4">

            <p class="form-section-label">Keamanan</p>

<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label class="form-label fw-medium" style="font-size: 13px;">Password</label>
        <div class="input-group">
            <input type="password" name="password" id="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Min. 8 karakter" required>
            <button type="button" class="btn btn-outline-secondary btn-sm"
                    onclick="togglePassword('password', 'icon-password')">
                <i class="bi bi-eye" id="icon-password"></i>
            </button>
        </div>
        @error('password') <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6">
        <label class="form-label fw-medium" style="font-size: 13px;">Konfirmasi password</label>
        <div class="input-group">
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control"
                   placeholder="Ulangi password" required>
            <button type="button" class="btn btn-outline-secondary btn-sm"
                    onclick="togglePassword('password_confirmation', 'icon-confirm')">
                <i class="bi bi-eye" id="icon-confirm"></i>
            </button>
        </div>
    </div>
</div>

            <hr class="my-4">

            <p class="form-section-label">Role</p>

            <div class="mb-3">
                <span class="badge-role-fixed">
                    <span class="dot"></span> Admin
                </span>
            
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary btn-sm px-4 d-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
                <div class="d-flex gap-2">
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary btn-sm px-4">
                        Batal
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm px-4 d-flex align-items-center gap-1">
                        <i class="bi bi-check-lg"></i> Simpan
                    </button>
                </div>
            </div>

        </form>
    </div>

</div>
@endsection