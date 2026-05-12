{{-- resources/views/destinasi/index.blade.php --}}
@extends('layouts.app')

@section('content')
@php
    $prefix = $prefix ?? 'admin';
@endphp

<div>

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2 class="fw-bold mb-0" style="font-size:1.4rem; color:var(--text-main);">Destinasi Wisata</h2>
            <small style="color:var(--text-muted);">Kelola dan pantau semua destinasi wisata</small>
        </div>
        <a href="{{ route($prefix.'.destinasi.create') }}" class="btn-tambah-user">
            <i class="bi bi-plus-lg"></i> Tambah Destinasi
        </a>
    </div>

    {{-- SEARCH --}}
    <form method="GET" action="{{ route($prefix.'.destinasi.index') }}" class="mb-3">
        <div style="display:flex; gap:.5rem; max-width:400px;">
            <input type="text" name="keyword" value="{{ $keyword ?? '' }}"
                   placeholder="Cari nama atau lokasi..."
                   style="flex:1; padding:.45rem .8rem; font-size:.83rem; border:1.5px solid rgba(46,141,232,.2); border-radius:8px; outline:none; font-family:'Inter',sans-serif; color:var(--text-main); background:#fff;">
            <button type="submit"
                    style="padding:.45rem 1rem; background:var(--blue-mid); color:#fff; border:none; border-radius:8px; font-size:.83rem; font-weight:600; cursor:pointer; font-family:'Inter',sans-serif;">
                Cari
            </button>
        </div>
    </form>

    {{-- ALERT --}}
    @if(session('success'))
    <div style="background:#ecfdf5; border:1px solid #6ee7b7; color:#065f46; border-radius:9px; padding:.6rem 1rem; font-size:.83rem; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem;">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    {{-- GRID --}}
    <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:1rem;">
        @forelse($destinasi as $item)
        <div style="background:#fff; border-radius:12px; border:1.5px solid rgba(46,141,232,.12); box-shadow:0 2px 16px rgba(30,80,160,.06); overflow:hidden; display:flex; flex-direction:column;">

            {{-- FOTO --}}
            @if($item->foto)
                <img src="{{ $item->foto }}"
                     style="width:100%; height:130px; object-fit:cover;">
            @else
                <div style="width:100%; height:130px; background:var(--blue-light); display:flex; align-items:center; justify-content:center;">
                    <i class="bi bi-image" style="font-size:24px; color:var(--blue-mid);"></i>
                </div>
            @endif

            {{-- BODY --}}
            <div style="padding:.85rem 1rem; flex:1; display:flex; flex-direction:column; gap:.3rem;">

                {{-- NAMA + KATEGORI --}}
                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:.5rem;">
                    <div style="font-weight:700; font-size:.875rem; color:var(--text-main); line-height:1.3;">{{ $item->nama }}</div>
                    <span style="background:#e8f3fd; color:#0C447C; border:0.5px solid #85B7EB; font-size:10px; font-weight:500; padding:2px 7px; border-radius:20px; white-space:nowrap; flex-shrink:0;">
                        {{ $item->kategori->nama_kategori ?? '-' }}
                    </span>
                </div>

                {{-- LOKASI --}}
                <div style="font-size:.78rem; color:var(--text-muted);">
                    <i class="bi bi-geo-alt" style="font-size:11px;"></i> {{ $item->lokasi }}
                </div>

                {{-- ALAMAT --}}
                <div style="font-size:.75rem; color:var(--text-muted);">
                    <i class="bi bi-house-door" style="font-size:11px;"></i> {{ Str::limit($item->alamat_lengkap, 45) }}
                </div>

                {{-- DIVIDER --}}
                <div style="border-top:1px solid #f0f6ff; margin:.3rem 0;"></div>

                {{-- HARGA + JAM --}}
                <div style="display:flex; gap:.5rem; font-size:.75rem;">
                    <div>
                        <span style="color:var(--text-muted);">Weekday</span><br>
                        <span style="font-weight:600; color:var(--blue-mid);">Rp {{ number_format($item->harga_tiket_weekday,0,',','.') }}</span>
                    </div>
                    <div style="border-left:1px solid #e5e7eb; margin:0 .25rem;"></div>
                    <div>
                        <span style="color:var(--text-muted);">Weekend</span><br>
                        <span style="font-weight:600; color:#0d9488;">Rp {{ number_format($item->harga_tiket_weekend,0,',','.') }}</span>
                    </div>
                    <div style="border-left:1px solid #e5e7eb; margin:0 .25rem;"></div>
                    <div>
                        <span style="color:var(--text-muted);">Jam</span><br>
                        <span style="font-weight:500; color:var(--text-main); font-size:.72rem;">{{ $item->weekday }}</span>
                    </div>
                </div>

                {{-- AKSI --}}
                <div style="display:flex; gap:.4rem; margin-top:auto; padding-top:.5rem;">
                    <a href="{{ route($prefix.'.destinasi.edit', $item->id_destinasi) }}"
                       style="display:inline-flex; align-items:center; gap:.3rem; padding:.35rem .75rem; font-size:.78rem; font-weight:500; color:var(--blue-mid); background:var(--blue-light); border:0.5px solid #85B7EB; border-radius:7px; text-decoration:none; transition:background .15s;"
                       onmouseover="this.style.background='#d0e8f8'" onmouseout="this.style.background='var(--blue-light)'">
                        <i class="bi bi-pencil" style="font-size:11px;"></i> Edit
                    </a>

                    <form action="{{ route($prefix.'.destinasi.destroy', $item->id_destinasi) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus destinasi ini?')" style="margin:0;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                style="display:inline-flex; align-items:center; gap:.3rem; padding:.35rem .75rem; font-size:.78rem; font-weight:500; color:#A32D2D; background:#fff0f0; border:0.5px solid #f5c6c6; border-radius:7px; cursor:pointer; font-family:'Inter',sans-serif; transition:background .15s;"
                                onmouseover="this.style.background='#FCEBEB'" onmouseout="this.style.background='#fff0f0'">
                            <i class="bi bi-trash" style="font-size:11px;"></i> Hapus
                        </button>
                    </form>
                </div>

            </div>
        </div>
        @empty
        <div style="grid-column:span 3; text-align:center; padding:2.5rem; color:var(--text-muted); font-size:13px;">
            <i class="bi bi-inbox" style="font-size:28px; display:block; margin-bottom:.5rem;"></i>
            Belum ada data destinasi.
        </div>
        @endforelse
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center mt-3">
        {{ $destinasi->links() }}
    </div>

</div>
@endsection