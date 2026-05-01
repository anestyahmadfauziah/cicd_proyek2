<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use App\Models\Destinasi;
use App\Models\User;
use App\Models\ActivityLog;

class DashboardController extends Controller
{
    public function index()
    {
        // ================= AMANIN GUARD =================
        $isSuperAdmin = Auth::guard('superadmin')->check();

        if ($isSuperAdmin) {
            Auth::shouldUse('superadmin');
            $user = Auth::guard('superadmin')->user();
        } else {
            Auth::shouldUse('web');
            $user = Auth::guard('web')->user();
        }

        // ❗ FIX ERROR NULL USER
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $adminId = $user->id_admin; // ✅ SELALU AMBIL UUID/ID ASLI USER

        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // ================= SUPERADMIN =================
        if ($isSuperAdmin) {

            $totalDestinasi = Destinasi::count();
            $totalUsers = User::count();

            $transaksiBulanIni = DB::table('pemesanan')
                ->whereMonth('tanggal_pemesanan', $bulanIni)
                ->whereYear('tanggal_pemesanan', $tahunIni)
                ->count();

            $pendapatanBulanIni = DB::table('pembayaran')
                ->whereMonth('tanggal_bayar', $bulanIni)
                ->whereYear('tanggal_bayar', $tahunIni)
                ->sum('total_bayar');
        }

        // ================= ADMIN =================
        else {

            // ❗ FIX UUID/INT ISSUE (WAJIB ID ASLI USER)
           $totalDestinasi = Destinasi::where('created_by_role', 'admin')
        ->where(function($q) use ($adminId) {
            $q->where('created_by_id', $adminId)
              ->orWhere('id_admin', $adminId);
        })
        ->count();
            $totalUsers = null;

            $transaksiBulanIni = DB::table('pemesanan as p')
                ->join('destinasi as d', 'p.id_destinasi', '=', 'd.id_destinasi')
                ->where('d.created_by_id', $adminId)
                ->whereBetween('p.tanggal_pemesanan', [
                    now()->startOfMonth(),
                    now()->endOfMonth()
                ])
                ->count();

            $pendapatanBulanIni = DB::table('pembayaran as pay')
                ->join('pemesanan as p', 'pay.id_pemesanan', '=', 'p.id_pemesanan')
                ->join('destinasi as d', 'p.id_destinasi', '=', 'd.id_destinasi')
                ->where('d.created_by_id', $adminId)
                ->whereMonth('pay.tanggal_bayar', $bulanIni)
                ->whereYear('pay.tanggal_bayar', $tahunIni)
                ->sum('total_bayar');
        }

        $totalTransaksi = $transaksiBulanIni ?? 0;
        $pendapatan = $pendapatanBulanIni ?? 0;

        $activities = ActivityLog::latest()->limit(5)->get();

        return view('dashboard', compact(
            'totalDestinasi',
            'totalUsers',
            'totalTransaksi',
            'pendapatan',
            'transaksiBulanIni',
            'pendapatanBulanIni',
            'activities'
        ));
    }

    public function destinasi()
    {
        return view('destinasi.index');
    }

    public function detail($id)
    {
        return view('destinasi.detail', compact('id'));
    }

    public function rekomendasi()
    {
        return view('rekomendasi.index');
    }

    public function users()
    {
        return view('user.index');
    }

    public function transaksi()
    {
        $isSuperAdmin = Auth::guard('superadmin')->check();
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $adminId = $user->id;

        // ================= SUMMARY =================
        $summaryRevenue = DB::table('pembayaran as pay')
            ->join('pemesanan as p','pay.id_pemesanan','=','p.id_pemesanan')
            ->join('destinasi as d','p.id_destinasi','=','d.id_destinasi');

        $summaryPengunjung = DB::table('pemesanan as p')
            ->join('destinasi as d','p.id_destinasi','=','d.id_destinasi');

        $summaryTransaksi = DB::table('pemesanan as p')
            ->join('destinasi as d','p.id_destinasi','=','d.id_destinasi');

        $summaryPending = DB::table('pemesanan as p')
            ->join('destinasi as d','p.id_destinasi','=','d.id_destinasi')
            ->where('p.status','pending');

        if (!$isSuperAdmin) {
            $summaryRevenue->where('d.created_by_id', $adminId);
            $summaryPengunjung->where('d.created_by_id', $adminId);
            $summaryTransaksi->where('d.created_by_id', $adminId);
            $summaryPending->where('d.created_by_id', $adminId);
        }

        $summary = [
            'revenue' => $summaryRevenue->sum('total_bayar'),
            'pengunjung' => $summaryPengunjung->sum('jumlah_tiket'),
            'transaksi' => $summaryTransaksi->count(),
            'pending' => $summaryPending->count(),
        ];

        // ================= TRANSAKSI =================
        $transactions = DB::table('pemesanan as p')
            ->join('users as u', 'p.user_id', '=', 'u.id')
            ->join('destinasi as d','p.id_destinasi','=','d.id_destinasi')
            ->leftJoin('pembayaran as pay','pay.id_pemesanan','=','p.id_pemesanan');

        if (!$isSuperAdmin) {
            $transactions->where('d.created_by_id', $adminId);
        }

        $transactions = $transactions
            ->select(
                'p.id_pemesanan as kode',
                'p.tanggal_pemesanan as tanggal',
                'u.name as customer',
                'd.nama as lokasi',
                DB::raw("'Reguler Dewasa' as tiket"),
                'p.jumlah_tiket as jumlah',
                'pay.metode_bayar as pembayaran',
                'p.total_harga as total',
                'pay.status_pembayaran as status'
            )
            ->orderBy('p.tanggal_pemesanan','desc')
            ->get();

        // ================= TREND =================
        $trend = $transactions->groupBy(function ($t) {
            return date('Y-m-d', strtotime($t->tanggal));
        })->map(function ($items, $tanggal) {
            return [
                'tanggal' => $tanggal,
                'revenue' => $items->sum('total'),
                'pengunjung' => $items->sum('jumlah')
            ];
        })->values();

        // ================= METODE =================
        $metodePembayaran = $transactions->groupBy(function ($t) {
            return $t->pembayaran ?? 'Lainnya';
        })->map(function ($items, $metode) {
            return [
                'metode' => $metode,
                'total' => $items->count()
            ];
        })->values();

        return view('transaksi.index', [
            'summary' => $summary,
            'transactions' => $transactions,
            'trend' => $trend,
            'metodePembayaran' => $metodePembayaran
        ]);
    }

    //ADMIN

    public function adminPengaturan()
{
    $user = auth()->user(); // ambil user yang login

    return view('admin.settings', compact('user'));
}

    public function updateProfile(Request $request)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'nullable|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'nullable|string|max:20',
        'bio' => 'nullable|string',
        'location' => 'nullable|string|max:255',
    ]);

    // 🔥 Ambil user dari guard yang aktif
    if (auth('superadmin')->check()) {
        $user = auth('superadmin')->user();
    } else {
        $user = auth('web')->user();
    }

    // 🔥 Safety (biar ga error lagi)
    if (!$user) {
        return back()->with('error', 'User tidak ditemukan, silakan login ulang');
    }

    $user->update([
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
        'email' => $request->email,
        'phone' => $request->phone,
        'bio' => $request->bio,
        'location' => $request->location,
    ]);

    return back()->with('success', 'Profil berhasil disimpan');
}

    public function updatePassword(Request $request)
{
    $request->validate([
        'password_lama' => 'required',
        'password_baru' => 'required|min:6|confirmed',
    ]);

    // ✅ Cek guard yang aktif
    if (auth('superadmin')->check()) {
        $user = auth('superadmin')->user();
    } else {
        $user = auth('web')->user();
    }

    if (!$user) {
        return back()->with('error', 'User tidak ditemukan, silakan login ulang');
    }

    if (!Hash::check($request->password_lama, $user->password)) {
        return back()->with('error', 'Password lama salah!');
    }

    $user->update([
        'password' => Hash::make($request->password_baru)
    ]);

    return back()->with('success', 'Password berhasil diubah!');
}
    //SUPERADMIN
    public function superadminPengaturan()
{
    $user = auth('superadmin')->user(); // ambil superadmin login

    return view('superadmin.settings', compact('user'));
}


}