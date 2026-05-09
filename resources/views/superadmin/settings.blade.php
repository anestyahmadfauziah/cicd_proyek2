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
    <div class="mb-4">
        <h2 class="fw-bold">Pengaturan Super Admin</h2>
        <p class="text-muted">Kelola profil, keamanan, dan hak akses sistem</p>
    </div>

    {{-- TAB --}}
    <div class="bg-white p-2 rounded-4 shadow-sm d-inline-flex gap-2 mb-4">
        <button class="btn btn-light tab-btn" data-tab="profil">Profil</button>
        <button class="btn btn-light tab-btn" data-tab="keamanan">Keamanan</button>
        <button class="btn btn-light tab-btn" data-tab="akses">Hak Akses</button>
        <button class="btn btn-light tab-btn" data-tab="rekap">Rekap Transaksi</button>
        <button class="btn btn-light tab-btn" data-tab="kategori">Kategori</button>
    </div>

    {{-- ================= PROFIL ================= --}}
    <div id="profil" class="tab-content d-none">
        <form method="POST" action="{{ route('superadmin.updateProfile') }}" enctype="multipart/form-data">
            @csrf

            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h5 class="fw-bold mb-1">Informasi Profil</h5>
                <p class="text-muted mb-4">Perbarui informasi profil Anda</p>

                <div class="d-flex align-items-center gap-4 mb-4">
                    <img src="{{ $user->photo ? asset($user->photo) : 'https://via.placeholder.com/100' }}"
                         class="rounded-circle"
                         width="90" height="90"
                         style="object-fit: cover;">

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
                        <input type="text" name="first_name" class="form-control" value="{{ $user->first_name }}">
                    </div>

                    <div class="col-md-6">
                        <label>Nama Belakang</label>
                        <input type="text" name="last_name" class="form-control" value="{{ $user->last_name }}">
                    </div>

                    <div class="col-12">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}">
                    </div>

                    <div class="col-12">
                        <label>Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                    </div>

                    <div class="col-12">
                        <label>Bio</label>
                        <textarea name="bio" class="form-control">{{ $user->bio }}</textarea>
                    </div>

                    <div class="col-12">
                        <label>Lokasi</label>
                        <input type="text" name="location" class="form-control" value="{{ $user->location }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">
                    Simpan Profil
                </button>
            </div>
        </form>
    </div>

    {{-- ================= KEAMANAN ================= --}}
    <div id="keamanan" class="tab-content d-none">
        <div class="card p-4 shadow-sm">
            <h5 class="mb-3">Ubah Password</h5>

            <form method="POST" action="{{ route('superadmin.updatePassword') }}">
                @csrf

                <div class="mb-3">
                    <label>Password Lama</label>
                    <div class="input-group">
                        <input type="password" name="password_lama" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Password Baru</label>
                    <div class="input-group">
                        <input type="password" name="password_baru" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="mb-3">
                    <label>Konfirmasi Password</label>
                    <div class="input-group">
                        <input type="password" name="password_baru_confirmation" class="form-control password-field">
                        <span class="input-group-text toggle-password" style="cursor:pointer;">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <button class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>

    {{-- ================= HAK AKSES ================= --}}
    <div id="akses" class="tab-content d-none">
        <div class="card p-4 shadow-sm">

            <h5 class="mb-3">Hak Akses Sistem</h5>

            <h6>Admin Wisata</h6>
            <ul>
                <li>✔ Kelola Destinasi (Tambah, Edit, Hapus)</li>
                <li>✔ Melihat Transaksi</li>
                <li>✔ Cetak Laporan</li>
            </ul>

            <h6 class="mt-3">Super Admin</h6>
            <ul>
                <li>✔ Kelola Semua Data</li>
                <li>✔ Kelola User</li>
                <li>✔ Rekap & Monitoring Transaksi</li>
            </ul>

        </div>
    </div>

    {{-- ================= REKAP ================= --}}
    <div id="rekap" class="tab-content d-none">

        <div class="card p-4 shadow-sm">

            <div class="d-flex justify-content-between mb-3">
                <h5>Rekap Transaksi</h5>

                <a href="{{ route('superadmin.rekap.transaksi.pdf', request()->all() + ['tab'=>'rekap']) }}"
                   class="btn text-white fw-semibold px-4 py-2 rounded-4 shadow-sm"
                   style="background:#2563eb;">
                    Cetak Data
                </a>
            </div>

            <form method="GET" action="{{ route('superadmin.rekap.transaksi') }}" class="row mb-3">
                <input type="hidden" name="tab" value="rekap">

                <div class="col-md-3">
                    <label>Bulan</label>
                    <select name="bulan" class="form-control">
                        <option value="">Semua</option>
                        @foreach(range(1,12) as $b)
                        <option value="{{ $b }}" {{ request('bulan') == $b ? 'selected' : '' }}>
                            {{ date('F', mktime(0,0,0,$b,1)) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Tahun</label>
                    <select name="tahun" class="form-control">
                        <option value="">Semua</option>
                        @foreach($years ?? [] as $y)
                        <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary">Filter</button>
                </div>
            </form>

            <div class="alert alert-info">
                Total Transaksi : <b>{{ $totalTransaksi ?? 0 }}</b><br>
                Total Pendapatan : <b>Rp {{ number_format($totalPendapatan ?? 0) }}</b>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Customer</th>
                        <th>Destinasi</th>
                        <th>Tanggal</th>
                        <th>Jumlah Tiket</th>
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
                        <td>{{ $r->status }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>

    {{-- ================= KATEGORI ================= --}}
<div id="kategori" class="tab-content d-none">
    <div class="card p-4 shadow-sm border-0 rounded-4">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Kelola Kategori</h6>
            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambahKategori"
                    style="font-size: 0.82rem; border-radius: 8px;">
                <i class="bi bi-plus-lg"></i> Tambah Kategori
            </button>
        </div>

        @if(session('success_kategori'))
            <div class="alert alert-success py-2" style="font-size: 0.85rem;">{{ session('success_kategori') }}</div>
        @endif

        <table class="table align-middle" style="font-size: 0.85rem;">
            <thead>
                <tr style="background: #fafafa;">
                    <th style="font-size: 0.75rem;">No</th>
                    <th style="font-size: 0.75rem;">Nama Kategori</th>
                    <th style="font-size: 0.75rem;" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kategoris ?? [] as $i => $kat)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $kat->nama_kategori }}</td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            {{-- Edit --}}
                            <button class="btn btn-sm"
                                    style="border: 1.5px solid #2563eb; border-radius: 8px; padding: 4px 8px; background: transparent;"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditKategori{{ $kat->id_kategori }}">
                                <i class="bi bi-pencil" style="font-size: 0.8rem; color: #2563eb;"></i>
                            </button>
                            {{-- Hapus --}}
                            <form action="{{ route('superadmin.kategori.destroy', $kat->id_kategori) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm"
                                        style="border: 1.5px solid #DD2A2A; border-radius: 8px; padding: 4px 8px; background: transparent;"
                                        onclick="return confirm('Hapus kategori ini?')">
                                    <i class="bi bi-trash" style="font-size: 0.8rem; color: #DD2A2A;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>

                {{-- Modal Edit --}}
                <div class="modal fade" id="modalEditKategori{{ $kat->id_kategori }}" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title fw-bold">Edit Kategori</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <form action="{{ route('superadmin.kategori.update', $kat->id_kategori) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <label class="form-label" style="font-size: 0.85rem;">Nama Kategori</label>
                                    <input type="text" name="nama_kategori" class="form-control"
                                           style="font-size: 0.85rem;" value="{{ $kat->nama_kategori }}" required>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                @empty
                <tr>
                    <td colspan="3" class="text-center text-muted py-3" style="font-size: 0.85rem;">
                        Belum ada kategori
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Tambah Kategori --}}
<div class="modal fade" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-bold">Tambah Kategori</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('superadmin.kategori.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <label class="form-label" style="font-size: 0.85rem;">Nama Kategori</label>
                    <input type="text" name="nama_kategori" class="form-control"
                           style="font-size: 0.85rem;" placeholder="Contoh: Alam, Budaya..." required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT TAB --}}
<script>
function setActiveTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.add('d-none'));
    document.getElementById(tabName).classList.remove('d-none');

    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
}

document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.onclick = function() {
        const tab = this.dataset.tab;
        const url = new URL(window.location);
        url.searchParams.set('tab', tab);
        window.location.href = url.toString();
    }
});

const urlParams = new URLSearchParams(window.location.search);
const activeTab = urlParams.get('tab') || 'profil';
setActiveTab(activeTab);
</script>

{{-- SCRIPT SHOW HIDE PASSWORD --}}
<script>
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
</script>

@endsection