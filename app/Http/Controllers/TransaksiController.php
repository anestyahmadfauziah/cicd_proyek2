<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pemesanan;
use Barryvdh\DomPDF\Facade\Pdf;

class TransaksiController extends Controller
{
    // 🔥 HELPER AMBIL USER SESUAI GUARD
    private function getUser()
    {
        return auth()->guard('web')->user() 
            ?? auth()->guard('superadmin')->user();
    }

    // ================= HELPER STATUS =================
    private function formatStatus($status)
    {
        return match ($status) {
            'success', 'settlement', 'capture' => 'Paid',
            'pending' => 'Pending',
            'expire', 'cancel', 'deny' => 'Failed',
            default => ucfirst($status),
        };
    }

    // ================= DASHBOARD TRANSAKSI =================
    public function index()
    {
        $user = $this->getUser();

        $pemesanan = Pemesanan::with(['pembayaran','destinasi','user'])
            ->when($user && $user->role === 'admin', function ($q) use ($user) {
                $q->whereHas('destinasi', function ($q2) use ($user) {
                    $q2->where('id_admin', $user->id);
                });
            })
            ->get();

        $summary = [
            'revenue' => $pemesanan->sum(fn($t) => $t->pembayaran?->total_bayar ?? 0),
            'pengunjung' => $pemesanan->sum('jumlah_tiket'),
            'transaksi' => $pemesanan->count(),
            'pending' => $pemesanan->where('status','pending')->count()
        ];

        $trend = $pemesanan
            ->groupBy(fn($t) => $t->tanggal_pemesanan->format('Y-m-d'))
            ->map(function ($items, $tanggal) {
                return [
                    'tanggal' => $tanggal,
                    'revenue' => $items->sum(fn($t) => $t->pembayaran?->total_bayar ?? 0),
                    'pengunjung' => $items->sum('jumlah_tiket')
                ];
            })
            ->sortBy('tanggal')
            ->values();

        $metodePembayaran = $pemesanan
            ->groupBy(fn($t) => $t->pembayaran?->metode_bayar ?? 'Lainnya')
            ->map(fn($items, $metode) => [
                'metode' => $metode,
                'total' => $items->count()
            ])
            ->values();

        $transactions = $pemesanan->map(fn($t) => [
            'kode' => $t->id_pemesanan,
            'tanggal' => $t->tanggal_pemesanan->format('d M Y'),
            'customer' => $t->user?->name ?? '-',
            'lokasi' => $t->destinasi?->nama ?? '-',
            'tiket' => 'Reguler Dewasa',
            'jumlah' => $t->jumlah_tiket,
            'pembayaran' => $t->pembayaran?->metode_bayar ?? '-',
            'total' => $t->pembayaran?->total_bayar ?? 0,

            // 🔥 FIX DI SINI
            'status' => $this->formatStatus($t->status)
        ])->toArray();

        return view('transaksi.index', compact(
            'summary',
            'transactions',
            'trend',
            'metodePembayaran'
        ));
    }

    // ================= REKAP TRANSAKSI =================
    public function rekap(Request $request)
    {
        $user = $this->getUser();

        $query = Pemesanan::with(['pembayaran','destinasi','user']);

        if ($user && $user->role === 'admin') {
            $query->whereHas('destinasi', function ($q) use ($user) {
                $q->where('id_admin', $user->id);
            });
        }

        if ($request->bulan) {
            $query->whereMonth('tanggal_pemesanan', $request->bulan);
        }

        if ($request->tahun) {
            $query->whereYear('tanggal_pemesanan', $request->tahun);
        }

        $rekap = $query->latest()->get();

        $totalTransaksi = $rekap->count();
        $totalPendapatan = $rekap->sum(fn($t) => $t->pembayaran?->total_bayar ?? 0);

        $years = Pemesanan::selectRaw('EXTRACT(YEAR FROM tanggal_pemesanan) as year')
            ->distinct()
            ->pluck('year')
            ->merge(range(date('Y') - 5, date('Y')))
            ->unique()
            ->sortDesc()
            ->values();

        $view = $user->role === 'admin'
            ? 'admin.settings'
            : 'superadmin.settings';

        return view($view, compact(
            'rekap',
            'totalTransaksi',
            'totalPendapatan',
            'years',
            'user'
        ));
    }

    // ================= EXPORT PDF REKAP =================
    public function rekapPDF(Request $request)
    {
        $user = $this->getUser();

        $query = Pemesanan::with(['pembayaran','destinasi','user']);

        if ($user && $user->role === 'admin') {
            $query->whereHas('destinasi', function ($q) use ($user) {
                $q->where('id_admin', $user->id);
            });
        }

        if ($request->bulan) {
            $query->whereMonth('tanggal_pemesanan', $request->bulan);
        }

        if ($request->tahun) {
            $query->whereYear('tanggal_pemesanan', $request->tahun);
        }

        $rekap = $query->get();

        $totalTransaksi = $rekap->count();
        $totalPendapatan = $rekap->sum(fn($t) => $t->pembayaran?->total_bayar ?? 0);

        $view = $user->role === 'admin'
            ? 'admin.rekap'
            : 'superadmin.rekap_pdf';

        $pdf = Pdf::loadView($view, compact(
            'rekap',
            'totalTransaksi',
            'totalPendapatan'
        ))->setPaper('A4', 'landscape');

        return $pdf->download('rekap-transaksi.pdf');
    }

    // ================= CETAK TRANSAKSI (PDF DETAIL) =================
    public function cetakIndex()
    {
        $query = Pemesanan::with(['pembayaran','destinasi','user']);

        // admin wisata
        if (auth()->guard('web')->check()) {

            $adminId = auth()->guard('web')->id();

            $destinasiIds = \App\Models\Destinasi::where('id_admin', $adminId)
                                ->pluck('id_destinasi');

            $query->whereIn('id_destinasi', $destinasiIds);
        }

        $pemesanan = $query->get();

        $pdf = Pdf::loadView('transaksi.cetak', compact('pemesanan'))
            ->setPaper('A4', 'landscape');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            "laporan-transaksi.pdf"
        );
    }
}