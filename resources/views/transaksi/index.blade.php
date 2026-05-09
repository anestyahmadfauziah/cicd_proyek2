@extends('layouts.app')

@section('content')
<div class="container-fluid">

    <h2 class="fw-bold mb-1">Laporan Transaksi Wisata</h2>

    {{-- ================= SUMMARY CARD ================= --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm p-3 rounded-4">
                <small>Total Revenue</small>
                <h3 class="text-primary fw-bold">
                    Rp {{ number_format($summary['revenue'], 0, ',', '.') }}
                </h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 rounded-4">
                <small>Total Pengunjung</small>
                <h3 class="text-success fw-bold">
                    {{ $summary['pengunjung'] }}
                </h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 rounded-4">
                <small>Total Transaksi</small>
                <h3 class="fw-bold text-purple">
                    {{ $summary['transaksi'] }}
                </h3>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm p-3 rounded-4">
                <small>Pending</small>
                <h3 class="fw-bold text-danger">
                    {{ $summary['pending'] }}
                </h3>
            </div>
        </div>
    </div>

    {{-- ================= GRAFIK ================= --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 rounded-4 h-100">
                <h5 class="fw-bold mb-3">Tren Revenue & Pengunjung Harian</h5>
                <div id="revenueChart"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 rounded-4 h-100">
                <h5 class="fw-bold mb-3">Metode Pembayaran</h5>
                <div id="paymentChart"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm p-4 border-0 rounded-4 h-100">
                <h5 class="fw-bold mb-3">Jenis Kelamin Pembeli</h5>

                {{-- Info angka real di atas chart --}}
                <div class="d-flex justify-content-around mb-2">
                    <div class="text-center">
                        <div class="fw-bold text-primary fs-5">{{ $lakiLaki }}</div>
                        <small class="text-muted">Laki-laki</small>
                    </div>
                    <div class="text-center">
                        <div class="fw-bold fs-5" style="color:#D4537E;">{{ $perempuan }}</div>
                        <small class="text-muted">Perempuan</small>
                    </div>
                </div>

                @if($totalGender === 0)
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-people fs-2 d-block mb-2"></i>
                        Belum ada data jenis kelamin pembeli
                    </div>
                @else
                    <div id="genderChart"></div>
                @endif
            </div>
        </div>

    </div>

    {{-- ================= TABEL ================= --}}
<div class="card shadow-sm p-4 border-0 rounded-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Daftar Transaksi</h6>

        @if(auth()->guard('web')->check())
        <a href="{{ route('admin.transaksi.cetak') }}"
        target="_blank"
        class="btn text-white fw-semibold px-3 py-1 rounded-4 shadow-sm"
        style="background:#2563eb; font-size: 0.85rem;">
            <i class="bi bi-printer me-2"></i> Cetak Data
        </a>
        @endif
    </div>

    <div class="table-responsive">
        <table class="table align-middle" style="font-size: 0.82rem;">
            <thead class="table-light">
                <tr>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Kode</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Tanggal</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Customer</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Lokasi</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Tiket</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Jumlah</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Pembayaran</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Total</th>
                    <th style="font-size: 0.75rem; letter-spacing: 0.03em;">Status</th>
                </tr>
            </thead>

            <tbody>
            @forelse($transactions as $t)
            <tr>
                <td>{{ $t->kode }}</td>
                <td>{{ \Carbon\Carbon::parse($t->tanggal)->format('d M Y') }}</td>
                <td>{{ $t->customer ?? '-' }}</td>
                <td>{{ $t->lokasi ?? '-' }}</td>
                <td>{{ $t->tiket }}</td>
                <td>{{ $t->jumlah }}</td>
                <td>{{ $t->pembayaran ?? '-' }}</td>
                <td>Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                <td>
                    @php
                        $statusClass = match(strtolower($t->status ?? '')) {
                            'success', 'settlement', 'capture', 'paid' => 'bg-success',
                            'pending' => 'bg-warning text-dark',
                            'expire', 'cancel', 'deny', 'failed' => 'bg-danger',
                            default => 'bg-secondary',
                        };
                        $statusLabel = match(strtolower($t->status ?? '')) {
                            'success', 'settlement', 'capture' => 'Success', 
                            'pending' => 'Pending',
                            'expire', 'cancel', 'deny', 'failed' => 'Failed',
                            default => ucfirst($t->status ?? '-'),
                        };
                    @endphp
                    <span class="badge {{ $statusClass }} rounded-pill px-2" style="font-size: 0.72rem;">{{ $statusLabel }}</span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="text-center text-muted py-4">
                    Belum ada data transaksi
                </td>
            </tr>
            @endforelse
            </tbody>

        </table>
    </div>

</div>

{{-- ================= SCRIPT GRAFIK ================= --}}
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // ===== DATA DARI CONTROLLER =====
    const trend           = @json($trend);
    const metode          = @json($metodePembayaran);
    const lakiPersen      = {{ $lakiPersen }};
    const perempuanPersen = {{ $perempuanPersen }};
    const totalGender     = {{ $totalGender }};
    const lakiLaki        = {{ $lakiLaki }};
    const perempuan       = {{ $perempuan }};

    // ===== TREN REVENUE & PENGUNJUNG =====
    const labels   = trend.map(t => t.tanggal);
    const revenue  = trend.map(t => t.revenue);
    const visitors = trend.map(t => t.pengunjung);

    new ApexCharts(document.querySelector("#revenueChart"), {
        chart: { type: 'line', height: 300 },
        stroke: { curve: 'smooth' },
        series: [
            { name: 'Revenue',   data: revenue  },
            { name: 'Visitors',  data: visitors }
        ],
        xaxis: { categories: labels },
        tooltip: {
            y: [{
                formatter: val => 'Rp ' + val.toLocaleString('id-ID')
            }, {
                formatter: val => val + ' orang'
            }]
        }
    }).render();

    // ===== METODE PEMBAYARAN =====
    new ApexCharts(document.querySelector("#paymentChart"), {
        chart: { type: 'pie', height: 300, width: '100%' },
        labels: metode.map(m => m.metode),
        series: metode.map(m => m.total),
        legend: {
            position: 'bottom'
        }
    }).render();

    // ===== JENIS KELAMIN (DATA REAL DARI DATABASE) =====
    @if($totalGender > 0)
    new ApexCharts(document.querySelector("#genderChart"), {
        chart: { type: 'radialBar', height: 300 },
        series: [lakiPersen, perempuanPersen],
        labels: ['Laki-laki', 'Perempuan'],
        colors: ['#378ADD', '#D4537E'],
        plotOptions: {
            radialBar: {
                dataLabels: {
                    name:  { fontSize: '13px' },
                    value: { fontSize: '14px' },
                    total: {
                        show: true,
                        label: 'Total',
                        formatter: () => totalGender + ' orang'
                    }
                }
            }
        },
        legend: {
            show: true,
            position: 'bottom',
            formatter: function(seriesName, opts) {
                const jumlah = seriesName === 'Laki-laki' ? lakiLaki : perempuan;
                return seriesName + ': ' + jumlah + ' orang';
            }
        },
        tooltip: {
            y: {
                formatter: val => val + '%'
            }
        }
    }).render();
    @endif

});

// ================= DOWNLOAD PDF =================
function downloadPDF() {
    fetch("{{ route('admin.transaksi.cetak') }}")
    .then(response => {
        // ← Cek content type dulu
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('text/html')) {
            // Artinya server return error page, bukan PDF
            return response.text().then(html => {
                console.error("Server error:", html);
                throw new Error("Server return HTML, bukan PDF");
            });
        }
        if (!response.ok) throw new Error("Gagal download");
        return response.blob();
    })
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "laporan-transaksi.pdf";
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(url);
    })
    .catch(err => {
        console.error(err);
        alert("Download gagal: " + err.message);
    });
}
</script>

@endsection