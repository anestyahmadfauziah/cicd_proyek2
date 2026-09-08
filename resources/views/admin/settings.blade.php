@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/admin/settings.css') }}">
@endsection

@section('content')

<div class="settings-wrapper">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    {{-- HEADER --}}
    <div class="page-header">
        <h2>Pengaturan Admin</h2>
        <p>Kelola pengaturan akun dan sistem wisata Anda</p>
    </div>

    {{-- TAB STRIP --}}
    <div class="tab-strip">
        <button class="tab-btn" data-tab="profil">Profil</button>
        <button class="tab-btn" data-tab="keamanan">Keamanan</button>
    </div>

    {{-- ===================== PROFIL ===================== --}}
    <div id="profil" class="tab-content d-none">
        <form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
            @csrf
            <div class="settings-card">
                <div class="card-title">Informasi Profil</div>
                <div class="card-subtitle">Perbarui informasi profil Anda</div>

                <div class="avatar-wrap">
                    <img src="{{ $user->foto_profil ?? 'https://via.placeholder.com/72' }}"
                    class="avatar-img" alt="Foto Profil">
                    <div class="avatar-info">
                        <h6>Foto Profil</h6>
                        <small>Format JPG, PNG · Maks 2MB</small>
                        <input type="file" name="foto_profil" class="form-control"
                               style="width:auto; font-size:0.78rem; padding: 0.3rem 0.7rem;">
                    </div>
                </div>

                <hr class="section-divider">

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-sm">Nama Depan</label>
                        <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-sm">Nama Belakang</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Bio</label>
                        <textarea name="bio" class="form-control" rows="3">{{ $user->bio }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label-sm">Lokasi</label>
                        <input type="text" name="location" class="form-control" value="{{ $user->location }}">
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn-save">Simpan Profil</button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===================== KEAMANAN ===================== --}}
    <div id="keamanan" class="tab-content d-none">
        <div class="settings-card" style="max-width: 560px;">
            <div class="card-title">Ubah Password</div>
            <div class="card-subtitle">Perbarui kata sandi akun Anda secara berkala</div>

            @if(session('error'))
                <div class="alert alert-danger" style="font-size:0.82rem;">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.updatePassword') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sm">Password Lama</label>
                    <div class="input-group">
                        <input type="password" name="password_lama" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-sm">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password_baru" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-sm">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_baru_confirmation" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>
    </div>

</div>

<script>
function setActiveTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('d-none'));
    const el = document.getElementById(tabName);
    if (el) el.classList.remove('d-none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    const btn = document.querySelector(`[data-tab="${tabName}"]`);
    if (btn) btn.classList.add('active');
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.onclick = function() {
        const tab = this.dataset.tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.location.href = url.toString();
    }
});

const activeTab = new URLSearchParams(window.location.search).get('tab') || 'profil';
setActiveTab(activeTab);

document.querySelectorAll('.toggle-password').forEach(toggle => {
    toggle.addEventListener('click', function () {
        const input = this.previousElementSibling;
        const icon = this.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });
});
</script>

@endsection