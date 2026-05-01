<!DOCTYPE html>
<html>
<head>
    <title>Rekap Transaksi</title>
    <style>
        body{
            font-family: sans-serif;
            font-size:12px;
        }

        h3{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th, td{
            border:1px solid #000;
            padding:6px;
        }

        th{
            background:#f2f2f2;
        }

        .summary{
            margin-bottom:15px;
        }
    </style>
</head>
<body>

<h3>REKAP TRANSAKSI</h3>

<div class="summary">
    <p>Total Transaksi : <b>{{ $totalTransaksi }}</b></p>
    <p>Total Pendapatan : <b>Rp {{ number_format($totalPendapatan) }}</b></p>
</div>

<table>
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
@foreach($rekap as $i => $r)
<tr>
<td>{{ $i+1 }}</td>

<td>
{{ $r->user->name ?? '-' }}
</td>

<td>{{ $r->destinasi?->nama ?? '-' }}</td>

<td>
{{ \Carbon\Carbon::parse($r->tanggal_pemesanan)->format('d M Y') }}
</td>

<td>{{ $r->jumlah_tiket }}</td>

<td>Rp {{ number_format($r->pembayaran?->total_bayar ?? 0) }}</td>

<td>{{ ucfirst($r->midtrans_status ?? $r->status) }}</td>

</tr>
@endforeach
</tbody>

</table>

</body>
</html>