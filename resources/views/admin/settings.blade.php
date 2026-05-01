@extends('layouts.app')

@section('content')

<div class="container-fluid py-4">

{{-- NOTIFIKASI --}}
@if(session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

{{-- HEADER --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Pengaturan Admin</h2>
        <p class="text-muted mb-0">Kelola pengaturan akun dan sistem wisata Anda</p>
    </div>
</div>

{{-- TAB --}}
<div class="bg-white p-2 rounded-4 shadow-sm d-inline-flex gap-3 mb-4">
    <button class="btn btn-light tab-btn active" data-tab="profil">Profil</button>
    <button class="btn btn-light tab-btn" data-tab="keamanan">Keamanan</button>
</div>

{{-- ================= PROFIL ================= --}}
<div id="profil" class="tab-content">

<form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
@csrf

<div class="card border-0 shadow-sm rounded-4 p-4">

<h5 class="fw-bold mb-1">Informasi Profil</h5>
<p class="text-muted mb-4">Perbarui informasi profil Anda</p>

{{-- FOTO PROFIL --}}
<div class="d-flex align-items-center gap-4 mb-4">

<div class="position-relative">
<img src="{{ $user->photo ? asset($user->photo) : 'https://via.placeholder.com/100' }}"
class="rounded-circle"
width="90"
height="90"
style="object-fit: cover;">
</div>

<div>
<h6 class="fw-semibold mb-1">Foto Profil</h6>
<small class="text-muted d-block mb-2">JPG, PNG, max 2MB</small>

<input type="file" name="photo" class="form-control form-control-sm">
</div>

</div>

<hr>

<div class="row g-3 mt-2">

<div class="col-md-6">
<label>Nama Depan</label>
<input type="text" name="first_name" class="form-control"
value="{{ $user->first_name }}">
</div>

<div class="col-md-6">
<label>Nama Belakang</label>
<input type="text" name="last_name" class="form-control"
value="{{ $user->last_name }}">
</div>

<div class="col-12">
<label>Email</label>
<input type="email" name="email" class="form-control"
value="{{ $user->email }}">
</div>

<div class="col-12">
<label>Nomor Telepon</label>
<input type="text" name="phone" class="form-control"
value="{{ $user->phone }}">
</div>

<div class="col-12">
<label>Bio</label>
<textarea name="bio" class="form-control">{{ $user->bio }}</textarea>
</div>

<div class="col-12">
<label>Lokasi</label>
<input type="text" name="location" class="form-control"
value="{{ $user->location }}">
</div>

</div>

<button type="submit" class="btn btn-primary mt-4">
<i class="bi bi-save me-2"></i> Simpan Profil
</button>

</div>
</form>

</div>


{{-- ================= KEAMANAN ================= --}}
<div id="keamanan" class="tab-content d-none">

<div class="card border-0 shadow-sm rounded-4 p-4">

<h5 class="fw-bold mb-1">Ubah Password</h5>
<p class="text-muted mb-4">
Perbarui password Anda secara berkala untuk keamanan
</p>

@if(session('error'))
<div class="alert alert-danger">
{{ session('error') }}
</div>
@endif

<form method="POST" action="{{ route('admin.updatePassword') }}">
@csrf

<div class="mb-4">
    <label class="form-label fw-semibold">Password Saat Ini</label>
    <div class="input-group">
        <input type="password" name="password_lama" class="form-control password-field">
        <span class="input-group-text toggle-password" style="cursor:pointer;">
            <i class="bi bi-eye"></i>
        </span>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Password Baru</label>
    <div class="input-group">
        <input type="password" name="password_baru" class="form-control password-field">
        <span class="input-group-text toggle-password" style="cursor:pointer;">
            <i class="bi bi-eye"></i>
        </span>
    </div>
</div>

<div class="mb-4">
    <label class="form-label fw-semibold">Konfirmasi Password Baru</label>
    <div class="input-group">
        <input type="password" name="password_baru_confirmation" class="form-control password-field">
        <span class="input-group-text toggle-password" style="cursor:pointer;">
            <i class="bi bi-eye"></i>
        </span>
    </div>
</div>

<button type="submit" class="btn btn-primary px-4 py-2">
<i class="bi bi-lock me-2"></i> Ubah Password
</button>

</form>

</div>
</div>

</div>


{{-- SCRIPT --}}
<script>
document.addEventListener("DOMContentLoaded", function(){

// TAB
document.querySelectorAll('.tab-btn').forEach(btn => {
btn.onclick = function() {
document.querySelectorAll('.tab-content')
.forEach(c => c.classList.add('d-none'));

document.getElementById(this.dataset.tab)
.classList.remove('d-none');

document.querySelectorAll('.tab-btn')
.forEach(b => b.classList.remove('active'));

this.classList.add('active');
}
});

// SHOW HIDE PASSWORD
document.querySelectorAll('.toggle-password').forEach(toggle => {
toggle.addEventListener('click', function () {

const input = this.previousElementSibling;
const icon = this.querySelector('i');

if (input.type === "password") {
input.type = "text";
icon.classList.remove("bi-eye");
icon.classList.add("bi-eye-slash");
} else {
input.type = "password";
icon.classList.remove("bi-eye-slash");
icon.classList.add("bi-eye");
}

});
});

});
</script>

<style>
.tab-btn.active {
background-color: #e9f2ff;
color: #0d6efd;
font-weight: 600;
}
</style>

@endsection