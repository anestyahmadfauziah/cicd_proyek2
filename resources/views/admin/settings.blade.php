@extends('layouts.app')

@section('content')

<style>
/* ===== WRAPPER ===== */
.settings-wrapper {
    padding: 1.5rem 2rem;
    max-width: 960px;
}

/* ===== HEADER ===== */
.page-header h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.2rem;
}
.page-header p {
    font-size: 0.875rem;
    color: #64748b;
    margin-bottom: 1.5rem;
}

/* ===== TAB STRIP ===== */
.tab-strip {
    display: flex;
    gap: 0.4rem;
    margin-bottom: 1.5rem;
    flex-wrap: wrap;
    width: fit-content;
    background: #fff;
    border-radius: 50px;
    padding: 4px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.tab-btn {
    border: 1px solid #e2e8f0;
    background: #fff;
    border-radius: 50px;
    padding: 6px 18px;
    font-size: 0.82rem;
    font-weight: 500;
    color: #475569;
    cursor: pointer;
    transition: all 0.18s ease;
}
.tab-btn:hover {
    background: #f1f5ff;
    color: #2563eb;
    border-color: #c7d7ff;
}
.tab-btn.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
    font-weight: 600;
}

/* ===== CARD ===== */
.settings-card {
    background: #fff;
    border-radius: 14px;
    padding: 1.75rem 2rem;
    box-shadow: 0 1px 4px rgba(0,0,0,0.07);
    margin-bottom: 1.5rem;
}
.card-title {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 0.2rem;
}
.card-subtitle {
    font-size: 0.8rem;
    color: #94a3b8;
    margin-bottom: 1.25rem;
}

/* ===== AVATAR ===== */
.avatar-wrap {
    display: flex;
    align-items: center;
    gap: 1.25rem;
    background: #f8faff;
    border: 1.5px dashed #c9d8f0;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    margin-bottom: 1.25rem;
}
.avatar-img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e2e8f0;
    flex-shrink: 0;
}
.avatar-info h6 {
    font-size: 0.85rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 0.1rem;
}
.avatar-info small {
    font-size: 0.75rem;
    color: #94a3b8;
    display: block;
    margin-bottom: 0.5rem;
}

/* ===== DIVIDER ===== */
.section-divider {
    border-color: #f1f5f9;
    margin: 1rem 0 1.25rem;
}

/* ===== FORM LABEL ===== */
.form-label-sm {
    font-size: 0.8rem;
    font-weight: 600;
    color: #475569;
    margin-bottom: 0.35rem;
    display: block;
}

/* ===== FORM CONTROL ===== */
.settings-wrapper .form-control {
    border-radius: 8px;
    border: 1px solid #e2e8f0;
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
    color: #1e293b;
    background: #fff;
}
.settings-wrapper .form-control:focus {
    border-color: #93c5fd;
    box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
}

/* ===== SAVE BUTTON ===== */
.btn-save {
    background: #2563eb;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 22px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.18s;
}
.btn-save:hover {
    background: #1d4ed8;
}

/* ===== ALERT ===== */
.alert-success-custom {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
    border-radius: 8px;
    padding: 0.65rem 1rem;
    font-size: 0.82rem;
    margin-bottom: 1rem;
}
</style>

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