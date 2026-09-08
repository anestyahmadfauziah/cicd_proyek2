@extends('layouts.app')

@push('styles')
    <link href="{{ asset('css/admin/settings.css') }}" rel="stylesheet">
@endpush

@section('content')

<div class="settings-wrapper">

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="alert-success-custom">{{ session('success') }}</div>
    @endif

    {{-- HEADER --}}
    <div class="page-header mb-1">
        <h2>Pengaturan Super Admin</h2>
    </div>

    {{-- TAB STRIP --}}
    <div class="tab-strip">
        <button class="tab-btn" data-tab="profil">Profil</button>
        <button class="tab-btn" data-tab="keamanan">Keamanan</button>
        <button class="tab-btn" data-tab="akses">Hak Akses</button>
        <button class="tab-btn" data-tab="rekap">Rekap Transaksi</button>
        <button class="tab-btn" data-tab="kategori">Kategori</button>
    </div>

    {{-- ===================== PROFIL ===================== --}}
    <div id="profil" class="tab-content d-none">
        <form method="POST" action="{{ route('superadmin.updateProfile') }}" enctype="multipart/form-data">
            @csrf
            <div class="settings-card">
                <div class="card-title">Informasi Profil</div>
                <div class="card-subtitle">Perbarui informasi profil Anda</div>

                {{-- Avatar --}}
                <div class="avatar-wrap">
                    <img src="{{ $user->foto_profil ?? 'https://via.placeholder.com/72' }}"
                    class="avatar-img" alt="Foto Profil">
                    <div class="avatar-info">
                        <h6>Foto Profil</h6>
                        <small>Format JPG, PNG · Maks 2MB</small>
                        <input type="file" name="foto_profil" class="form-control" style="width:auto; font-size:0.78rem; padding: 0.3rem 0.7rem;">
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
                        <textarea name="bio" class="form-control">{{ $user->bio }}</textarea>
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

            <form method="POST" action="{{ route('superadmin.updatePassword') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-sm">Password Lama</label>
                    <div class="input-group">
                        <input type="password" name="password_lama" class="form-control password-field">
                        <span class="input-group-text toggle-password">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-sm">Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password_baru" class="form-control password-field">
                        <span class="input-group-text toggle-password">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label-sm">Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_baru_confirmation" class="form-control password-field">
                        <span class="input-group-text toggle-password">
                            <i class="bi bi-eye" style="font-size:0.85rem;"></i>
                        </span>
                    </div>
                </div>
                <button type="submit" class="btn-save">Update Password</button>
            </form>
        </div>
    </div>

    {{-- ===================== HAK AKSES ===================== --}}
    <div id="akses" class="tab-content d-none">
        <div class="settings-card">
            <div class="card-title">Hak Akses Sistem</div>
            <div class="card-subtitle">Daftar izin berdasarkan peran pengguna</div>

            <div class="row g-3 mt-1">
                <div class="col-md-6">
                    <div class="role-card">
                        <h6>
                            <span style="display:inline-flex;align-items:center;gap:0.45rem;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#3b82f6;display:inline-block;"></span>
                                Admin Wisata
                            </span>
                        </h6>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Kelola Destinasi (Tambah, Edit, Hapus)
                        </div>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Melihat Transaksi
                        </div>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Cetak Laporan
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="role-card" style="border-color:#c7d7ff;background:#f5f8ff;">
                        <h6>
                            <span style="display:inline-flex;align-items:center;gap:0.45rem;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#2563eb;display:inline-block;"></span>
                                Super Admin
                            </span>
                        </h6>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Kelola Semua Data
                        </div>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Kelola User
                        </div>
                        <div class="access-item">
                            <span class="access-check"><svg viewBox="0 0 12 12"><polyline points="1.5,6 4.5,9 10.5,3"/></svg></span>
                            Rekap & Monitoring Transaksi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== REKAP ===================== --}}
    <div id="rekap" class="tab-content d-none">
        <div class="settings-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <div class="card-title" style="margin-bottom:0">Rekap Transaksi</div>
                    <div class="card-subtitle" style="margin-bottom:0">Data transaksi dan pendapatan</div>
                </div>
                <a href="{{ route('superadmin.rekap.transaksi.pdf', request()->all() + ['tab'=>'rekap']) }}" class="btn-cetak">
                    <i class="bi bi-printer" style="font-size:0.82rem;"></i> Cetak Data
                </a>
            </div>

            <form method="GET" action="{{ route('superadmin.rekap.transaksi') }}">
                <input type="hidden" name="tab" value="rekap">
                <div class="filter-row">
                    <div class="filter-group">
                        <label class="form-label-sm">Bulan</label>
                        <select name="bulan" class="form-control">
                            <option value="">Semua</option>
                            @foreach(range(1,12) as $b)
                            <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                                {{ date('F', mktime(0,0,0,$b,1)) }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label class="form-label-sm">Tahun</label>
                        <select name="tahun" class="form-control">
                            <option value="">Semua</option>
                            @foreach($years ?? [] as $y)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn-filter">Filter</button>
                </div>
            </form>

            <div class="summary-box">
                <div class="summary-item">
                    Total Transaksi<br><strong>{{ $totalTransaksi ?? 0 }}</strong>
                </div>
                <div class="summary-item">
                    Total Pendapatan<br><strong>Rp {{ number_format($totalPendapatan ?? 0) }}</strong>
                </div>
            </div>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Destinasi</th>
                        <th>Tanggal</th>
                        <th>Jml Tiket</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekap ?? [] as $i => $r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r->user?->name ?? '-' }}</td>
                        <td>{{ $r->destinasi?->nama ?? '-' }}</td>
                        <td>{{ $r->tanggal_pemesanan->format('d M Y') }}</td>
                        <td>{{ $r->jumlah_tiket }}</td>
                        <td>Rp {{ number_format($r->pembayaran?->total_bayar ?? 0) }}</td>
                        <td>
                            @php $st = strtolower($r->status); @endphp
                            <span class="badge-status {{ $st === 'sukses' || $st === 'success' ? 'badge-success' : ($st === 'pending' ? 'badge-pending' : 'badge-cancel') }}">
                                {{ $r->status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="7">Tidak ada data transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ===================== KATEGORI ===================== --}}
    <div id="kategori" class="tab-content d-none">
        <div class="settings-card">

            @if(session('success_kategori'))
                <div class="alert-success-custom">{{ session('success_kategori') }}</div>
            @endif

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="card-title" style="margin-bottom:0">Kelola Kategori</div>
                    <div class="card-subtitle" style="margin-bottom:0">Atur kategori destinasi wisata</div>
                </div>
                <button class="btn-add" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                    Tambah Kategori
                </button>
            </div>

            <table class="kategori-table">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th>Nama Kategori</th>
                        <th style="width:100px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kategoris ?? [] as $i => $kat)
                    <tr>
                        <td style="color:#94a3b8;font-weight:500;">{{ $i + 1 }}</td>
                        <td>{{ $kat->nama_kategori }}</td>
                        <td>
                            <div style="display:flex;justify-content:center;gap:0.5rem;">
                                <button class="btn-icon btn-icon-edit" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $kat->id_kategori }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                </button>
                                <form action="{{ route('superadmin.kategori.destroy', $kat->id_kategori) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-icon btn-icon-del" onclick="return confirm('Hapus kategori ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    {{-- Modal Edit --}}
                    <div class="modal fade" id="modalEditKategori{{ $kat->id_kategori }}" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h6 class="modal-title">Edit Kategori</h6>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form action="{{ route('superadmin.kategori.update', $kat->id_kategori) }}" method="POST">
                                    @csrf @method('PUT')
                                    <div class="modal-body">
                                        <label class="form-label-sm">Nama Kategori</label>
                                        <input type="text" name="nama_kategori" class="form-control" value="{{ $kat->nama_kategori }}" required>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn-modal-save">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:#94a3b8;padding:2rem;font-size:0.85rem;">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Tambah Kategori</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label-sm">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control" placeholder="Contoh: Alam, Budaya..." required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modal-save">Simpan</button>
                </div>
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

// Toggle password visibility
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