<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Wisata</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* ===== ROOT & BASE ===== */
        :root {
            --blue-deep:    #0f4c8a;
            --blue-mid:     #1a6bbf;
            --blue-bright:  #2e8de8;
            --blue-light:   #e8f3fd;
            --accent:       #38bdf8;
            --sidebar-w:    260px;
            --radius:       14px;
            --shadow-card:  0 2px 16px rgba(30, 80, 160, 0.08);
            --shadow-hover: 0 6px 24px rgba(30, 80, 160, 0.16);
            --text-main:    #1e2d40;
            --text-muted:   #7a94b0;
            --bg-page:      #f0f6ff;
            --border:       rgba(46, 141, 232, 0.12);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-page);
            color: var(--text-main);
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            background: linear-gradient(160deg, #0d3f77 0%, #1a6bbf 55%, #2e8de8 100%);
            position: fixed;
            top: 0; left: 0;
            display: flex;
            flex-direction: column;
            padding: 24px 16px;
            z-index: 100;
            box-shadow: 4px 0 24px rgba(15, 76, 138, 0.25);
            overflow-y: auto;
            overflow-x: hidden;
        }

        .sidebar::before,
        .sidebar::after {
            content: '';
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
        }
        .sidebar::before {
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.05);
            top: -60px; right: -60px;
        }
        .sidebar::after {
            width: 140px; height: 140px;
            background: rgba(255,255,255,0.04);
            bottom: 80px; left: -50px;
        }

        .sidebar-logo {
            text-align: center;
            padding: 8px 0 20px;
            border-bottom: 1px solid rgba(255,255,255,0.12);
            margin-bottom: 20px;
        }
        .sidebar-logo img {
            max-width: 160px;
            filter: brightness(0) invert(1);
            opacity: 0.95;
        }

        .sidebar-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.45);
            padding: 6px 12px 4px;
            margin-bottom: 4px;
        }

        .sidebar-menu { flex: 1; }

        .sidebar a {
            color: rgba(255,255,255,0.85);
            text-decoration: none;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 11px;
            border-radius: 10px;
            margin-bottom: 3px;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s, color 0.2s, transform 0.2s;
            position: relative;
        }
        .sidebar a i {
            font-size: 17px;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            opacity: 0.85;
        }
        .sidebar a:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            transform: translateX(4px);
        }
        .sidebar a:hover i { opacity: 1; }
        .sidebar a.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-weight: 600;
        }
        .sidebar a.active::before {
            content: '';
            position: absolute;
            left: 0; top: 50%;
            transform: translateY(-50%);
            width: 3px; height: 20px;
            background: var(--accent);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.1);
            margin: 12px 4px;
        }

        .sidebar-bottom { margin-top: auto; }

        .btn-logout {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.15);
            color: rgba(255,255,255,0.85);
            padding: 10px 14px;
            width: 100%;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 11px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
        }
        .btn-logout:hover {
            background: rgba(255,255,255,0.18);
            color: #fff;
        }
        .btn-logout i { font-size: 17px; width: 20px; text-align: center; }

        .sidebar-user {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: rgba(255,255,255,0.08);
            border-radius: 10px;
            margin-bottom: 10px;
        }
        .sidebar-user .avatar {
            width: 34px; height: 34px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; color: #fff; font-weight: 700; flex-shrink: 0;
        }
        .sidebar-user .user-info { flex: 1; overflow: hidden; }
        .sidebar-user .user-name {
            font-size: 13px; font-weight: 600; color: #fff;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .sidebar-user .user-role {
            font-size: 11px; color: rgba(255,255,255,0.55);
        }

        /* ===== MAIN CONTENT ===== */
        .content {
            margin-left: var(--sidebar-w);
            padding: 32px 36px;
            min-height: 100vh;
        }

        /* ===== TOP BAR ===== */
        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 28px;
            gap: 16px;
        }
        .topbar-title h1 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1.2;
        }
        .topbar-title p {
            font-size: 13px;
            color: var(--text-muted);
            margin-top: 2px;
        }
        .topbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .topbar-search { position: relative; }
        .topbar-search input {
            border: 1.5px solid var(--border);
            border-radius: 10px;
            padding: 8px 14px 8px 36px;
            font-size: 13px;
            background: #fff;
            color: var(--text-main);
            outline: none;
            width: 200px;
            transition: border-color 0.2s, width 0.3s;
            font-family: inherit;
        }
        .topbar-search input:focus {
            border-color: var(--blue-bright);
            width: 240px;
        }
        .topbar-search i {
            position: absolute;
            left: 11px; top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            font-size: 15px;
        }
        .topbar-icon-btn {
            width: 38px; height: 38px;
            border-radius: 10px;
            border: 1.5px solid var(--border);
            background: #fff;
            display: flex; align-items: center; justify-content: center;
            color: var(--text-muted);
            font-size: 16px;
            cursor: pointer;
            transition: border-color 0.2s, color 0.2s, background 0.2s;
            text-decoration: none;
        }
        .topbar-icon-btn:hover {
            border-color: var(--blue-bright);
            color: var(--blue-bright);
            background: var(--blue-light);
        }

        /* ===== STAT CARDS ===== */
        .card-stat {
            background: #fff;
            border-radius: var(--radius);
            padding: 22px 20px;
            box-shadow: var(--shadow-card);
            border: 1.5px solid var(--border);
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .card-stat:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }
        .card-stat .stat-icon {
            width: 50px; height: 50px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .card-stat .stat-text { flex: 1; }
        .card-stat .stat-value {
            font-size: 26px;
            font-weight: 700;
            color: var(--text-main);
            line-height: 1;
        }
        .card-stat .stat-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 500;
        }
        .card-stat .stat-badge {
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 20px;
            font-weight: 600;
        }

        .icon-blue   { background: #e8f3fd; color: #1a6bbf; }
        .icon-teal   { background: #e6f9f5; color: #0d9488; }
        .icon-orange { background: #fff3e8; color: #ea8c3b; }
        .icon-purple { background: #f0eaff; color: #7c3aed; }

        /* ===== CARD GENERIC ===== */
        .card-panel {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow-card);
            border: 1.5px solid var(--border);
            overflow: hidden;
        }
        .card-panel-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .card-panel-header h6 {
            font-size: 14px;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }
        .card-panel-header .header-badge {
            font-size: 11px;
            padding: 3px 9px;
            border-radius: 20px;
            background: var(--blue-light);
            color: var(--blue-mid);
            font-weight: 600;
        }
        .card-panel-body { padding: 16px 20px; }

        /* ===== ACTIVITY ITEMS ===== */
        .activity-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #f1f5fb;
            gap: 12px;
        }
        .activity-item:last-child { border-bottom: none; }
        .activity-item .act-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--blue-bright);
            flex-shrink: 0;
            margin-top: 2px;
        }
        .activity-item .act-info { flex: 1; }
        .activity-item strong {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-main);
        }
        .activity-item small {
            display: block;
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 1px;
        }
        .activity-item .badge {
            font-size: 11px;
            padding: 4px 9px;
            border-radius: 20px;
            font-weight: 600;
            white-space: nowrap;
        }

        /* ===== PRINT BUTTON ===== */
        .btn-cetak {
            background: linear-gradient(135deg, var(--blue-mid), var(--blue-deep));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(26, 107, 191, 0.3);
            transition: 0.2s ease;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-cetak:hover {
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(26, 107, 191, 0.4);
        }

        /* ===== TABLE ===== */
        .table-clean { width: 100%; border-collapse: collapse; font-size: 13px; }
        .table-clean thead tr th {
            padding: 10px 12px;
            background: var(--bg-page);
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1.5px solid var(--border);
            text-align: left;
        }
        .table-clean tbody tr td {
            padding: 11px 12px;
            border-bottom: 1px solid #f1f5fb;
            color: var(--text-main);
            vertical-align: middle;
        }
        .table-clean tbody tr:last-child td { border-bottom: none; }
        .table-clean tbody tr:hover td { background: #f8fbff; }

        /* ===== BREADCRUMB ===== */
        .breadcrumb-bar {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12.5px;
            color: var(--text-muted);
            margin-bottom: 6px;
        }
        .breadcrumb-bar a { color: var(--blue-bright); text-decoration: none; font-weight: 500; }
        .breadcrumb-bar a:hover { text-decoration: underline; }
        .breadcrumb-bar i { font-size: 10px; }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #c5d8ef; border-radius: 6px; }
        ::-webkit-scrollbar-thumb:hover { background: #9bbcd9; }

        /* ===== KELOLA USER ===== */
        .stat-simple {
            background: #f0f6ff;
            border-radius: 10px;
            padding: 14px 18px;
        }
        .stat-simple .stat-simple-label {
            font-size: 12px;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 6px;
        }
        .stat-simple .stat-simple-value {
            font-size: 26px;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-table-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-card);
            overflow: hidden;
        }
        .user-table-card table { font-size: 13px; }
        .user-table-card thead th {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--text-muted);
            background: var(--bg-page);
            padding: 10px 16px;
        }
        .user-table-card tbody td {
            padding: 12px 16px;
            vertical-align: middle;
        }
        .user-table-card tbody tr:hover td { background: #f8fbff; }

        .avatar-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e8f3fd;
            color: #0C447C;
            font-size: 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .badge-role-admin {
            background: #EAF3DE;
            color: #3B6D11;
            border: 0.5px solid #C0DD97;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-role-user {
            background: #E6F1FB;
            color: #0C447C;
            border: 0.5px solid #85B7EB;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-status-active {
            background: #EAF3DE;
            color: #3B6D11;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-status-inactive {
            background: #f1f1f1;
            color: #6c757d;
            font-size: 12px;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .badge-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
        }

        .btn-tambah-user {
            background: #E6F1FB;
            color: #0C447C;
            border: 0.5px solid #85B7EB;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: background 0.15s;
        }
        .btn-tambah-user:hover {
            background: #d0e8f8;
            color: #0C447C;
        }

        .btn-hapus {
            background: none;
            border: none;
            color: #adb5bd;
            border-radius: 6px;
            padding: 4px 6px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: color 0.15s, background 0.15s;
        }
        .btn-hapus:hover {
            color: #A32D2D;
            background: #FCEBEB;
        }

        /* ===== FORM TAMBAH USER ===== */
        .form-user-card {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            box-shadow: var(--shadow-card);
            padding: 1.5rem;
        }
        .form-section-label {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 1rem;
        }
        .badge-role-fixed {
            background: #EAF3DE;
            color: #3B6D11;
            border: 0.5px solid #C0DD97;
            font-size: 13px;
            font-weight: 500;
            border-radius: 8px;
            padding: 6px 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .badge-role-fixed .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #639922;
            display: inline-block;
        }

        /* ===== EDIT DESTINASI ===== */
        .edit-destinasi-wrapper {
            padding: 0;
        }
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.75rem;
        }
        .page-header h2 {
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--text-main);
            margin: 0;
        }
        .page-header p {
            font-size: 0.82rem;
            color: var(--text-muted);
            margin: 0.2rem 0 0;
        }
        .alert-success-custom {
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            border-radius: 10px;
            padding: .75rem 1.1rem;
            font-size: .875rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .card-section {
            background: #fff;
            border-radius: 12px;
            border: 1.5px solid var(--border);
            padding: 1.4rem 1.6rem;
            margin-bottom: 1.1rem;
            box-shadow: var(--shadow-card);
        }
        .card-section-title {
            font-size: 0.72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: var(--text-muted);
            margin-bottom: 1rem;
            padding-bottom: .55rem;
            border-bottom: 1px solid #f0f6ff;
        }
        .form-label-custom {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: .3rem;
            display: block;
        }
        .form-control-custom {
            width: 100%;
            padding: .5rem .8rem;
            font-size: .85rem;
            color: var(--text-main);
            background: var(--bg-page);
            border: 1.5px solid rgba(46,141,232,0.18);
            border-radius: 8px;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        .form-control-custom:focus {
            border-color: var(--blue-bright);
            background: #fff;
            box-shadow: 0 0 0 3px rgba(46,141,232,.1);
        }
        textarea.form-control-custom {
            resize: vertical;
            min-height: 88px;
        }
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        .file-upload-box {
            border: 1.5px dashed rgba(46,141,232,0.3);
            border-radius: 9px;
            padding: 1rem;
            text-align: center;
            cursor: pointer;
            transition: border-color .15s, background .15s;
            background: var(--bg-page);
        }
        .file-upload-box:hover {
            border-color: var(--blue-bright);
            background: var(--blue-light);
        }
        .file-upload-box input[type="file"] { display: none; }
        .file-upload-box label {
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: .35rem;
        }
        .file-upload-icon {
            width: 34px; height: 34px;
            background: var(--blue-light);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: .15rem;
            color: var(--blue-mid);
        }
        .file-upload-text { font-size: .8rem; color: var(--text-main); font-weight: 600; }
        .file-upload-sub  { font-size: .72rem; color: var(--text-muted); }
        .btn-submit {
            width: 100%;
            padding: .65rem;
            background: var(--blue-mid);
            color: #fff;
            font-size: .875rem;
            font-weight: 600;
            border: none;
            border-radius: 9px;
            cursor: pointer;
            transition: background .15s, transform .1s;
            margin-top: .4rem;
            font-family: 'Inter', sans-serif;
        }
        .btn-submit:hover { background: var(--blue-deep); transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }
        select.form-control-custom {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%237a94b0'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.7rem center;
            background-size: 1rem;
            padding-right: 2.2rem;
            cursor: pointer;
        }
        @media (max-width: 640px) {
            .grid-2 { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

@if(Auth::check())

<div class="sidebar">

    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="{{ asset('images/Logo.png') }}" alt="Logo">
    </div>

    <!-- Menu -->
    <div class="sidebar-menu">

        <div class="sidebar-label">Menu Utama</div>

        <a href="{{ route('dashboard') }}">
            <i class="bi bi-house-door"></i> Halaman Utama
        </a>

        {{-- Destinasi --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.destinasi.index') }}">
            <i class="bi bi-geo-alt"></i> Destinasi Wisata
        </a>
        @elseif(auth()->guard('web')->check())
        <a href="{{ route('admin.destinasi.index') }}">
            <i class="bi bi-geo-alt"></i> Destinasi Wisata
        </a>
        @endif

        {{-- Rekomendasi (superadmin) --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.rekomendasi.index') }}">
            <i class="bi bi-star"></i> Rekomendasi
        </a>
        @endif

        {{-- Users (superadmin) --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.users.index') }}">
            <i class="bi bi-people"></i> Kelola Users
        </a>
        @endif

        {{-- Transaksi --}}
        @if(auth()->guard('superadmin')->check())
        <div class="sidebar-label" style="margin-top:10px;">Keuangan</div>
        <a href="{{ route('superadmin.transaksi') }}">
            <i class="bi bi-cart3"></i> Transaksi
        </a>
        @elseif(auth()->guard('web')->check())
        <div class="sidebar-label" style="margin-top:10px;">Keuangan</div>
        <a href="{{ route('admin.transaksi.index') }}">
            <i class="bi bi-cart3"></i> Transaksi
        </a>
        @endif

    </div>

    <!-- Bottom -->
    <div class="sidebar-bottom">
        <hr class="sidebar-divider">

        {{-- User chip --}}
        @php
            $sidebarUser = auth()->guard('superadmin')->check()
                ? auth()->guard('superadmin')->user()
                : auth()->guard('web')->user();

            $displayName = trim(($sidebarUser->first_name ?? '') . ' ' . ($sidebarUser->last_name ?? ''));
            if (!$displayName) $displayName = $sidebarUser->name ?? 'User';
        @endphp

        @if($sidebarUser)
        <div class="sidebar-user">
            <div class="avatar">{{ strtoupper(substr($displayName, 0, 1)) }}</div>
            <div class="user-info">
                <div class="user-name">{{ $displayName }}</div>
                <div class="user-role">
                    @if(auth()->guard('superadmin')->check()) Super Admin
                    @else Admin @endif
                </div>
            </div>
        </div>
        @endif

        {{-- Pengaturan --}}
        @if(auth()->guard('superadmin')->check())
        <a href="{{ route('superadmin.pengaturan') }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
        @elseif(auth()->guard('web')->check())
        <a href="{{ route('admin.pengaturan') }}">
            <i class="bi bi-gear"></i> Pengaturan
        </a>
        @endif

        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST" style="margin-top:6px;">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Logout
            </button>
        </form>

    </div>

</div>

@endif

<div class="content">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
function togglePassword(fieldId, iconId) {
    const field = document.getElementById(fieldId);
    const icon  = document.getElementById(iconId);
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}
</script>

</body>
</html>