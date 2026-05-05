<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi Wisata</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
            margin-bottom: 4px;
        }

        .subtitle {
            text-align: center;
            margin-bottom: 20px;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
        }

        table th {
            background: #f2f2f2;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary {
            margin-bottom: 15px;
        }

        .badge-berhasil { color: green; font-weight: bold; }
        .badge-menunggu { color: orange; font-weight: bold; }
        .badge-gagal    { color: red; font-weight: bold; }
    </style>
</head>
<body>

<h2>LAPORAN TRANSAKSI WISATA</h2>
<p class="subtitle">Dicetak pada: {{ \Carbon\Carbon::now()->format('d-m-Y H:i') }}</p>

<div class="summary">
    <p><b>Total Transaksi:</b> {{ count($pemesanan) }}</p>
    <p><b>Total Pendapatan:</b> 
        Rp {{ number_format($pemesanan->sum(fn($t) => $t->pembayaran?->total_bayar ?? 0), 0, ',', '.') }}
    </p>
</div>

<table>
    <thead>
        <tr>
            <th class="text-center">No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Destinasi</th>
            <th class="text-center">Jumlah Tiket</th>
            <th>Pembayaran</th>
            <th class="text-right">Total</th>
            <th class="text-center">Status</th>
        </tr>
    </thead>
    <tbody>
    @forelse($pemesanan as $i => $t)
    <tr>
        <td class="text-center">{{ $i + 1 }}</td>
        <td>{{ $t->id_pemesanan }}</td>
        <td>{{ \Carbon\Carbon::parse($t->tanggal_pemesanan)->format('d-m-Y') }}</td>
        <td>{{ $t->user?->name ?? '-' }}</td>
        <td>{{ $t->destinasi?->nama ?? '-' }}</td>
        <td class="text-center">{{ $t->jumlah_tiket }}</td>
        <td>{{ $t->pembayaran?->metode_bayar ?? '-' }}</td>
        <td class="text-right">Rp {{ number_format($t->pembayaran?->total_bayar ?? 0, 0, ',', '.') }}</td>
        <td class="text-center">
            @if(in_array($t->status, ['success', 'settlement', 'capture', 'paid']))
                <span class="badge-berhasil">Berhasil</span>
            @elseif($t->status === 'pending')
                <span class="badge-menunggu">Menunggu</span>
            @else
                <span class="badge-gagal">{{ ucfirst($t->status ?? '-') }}</span>
            @endif
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="9" class="text-center">Tidak ada data transaksi</td>
    </tr>
    @endforelse
    </tbody>
</table>

</body>
</html>