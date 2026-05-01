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
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table th {
            background: #f2f2f2;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<h2>LAPORAN TRANSAKSI WISATA</h2>

<div class="summary">
    <p><b>Total Data:</b> {{ count($pemesanan) }}</p>
</div>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Destinasi</th>
            <th>Jumlah Tiket</th>
            <th>Pembayaran</th>
            <th>Total</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>
@forelse($pemesanan as $t)
<tr>
<td>{{ $t->id_pemesanan }}</td>
<td>{{ $t->tanggal_pemesanan->format('d-m-Y') }}</td>
<td>{{ $t->user?->name ?? '-' }}</td>
<td>{{ $t->destinasi?->nama ?? '-' }}</td>
<td>{{ $t->jumlah_tiket }}</td>
<td>{{ $t->pembayaran?->metode_bayar ?? '-' }}</td>
<td>Rp {{ number_format($t->pembayaran?->total_bayar ?? 0,0,',','.') }}</td>
<td>
@if($t->status == 'paid')
    Berhasil
@elseif($t->status == 'pending')
    Menunggu
@else
    {{ $t->status }}
@endif
</td>
</tr>

@empty
<tr>
<td colspan="8" style="text-align:center;">
Tidak ada data transaksi
</td>
</tr>
@endforelse
</tbody>
</table>

</body>
</html>