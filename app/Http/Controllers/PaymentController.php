<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Pemesanan;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function createTransaction(Request $request)
    {
        $request->validate([
            'total'             => 'required|numeric|min:1',
            'name'              => 'required|string',
            'email'             => 'required|email',
            'user_id'           => 'required|uuid',
            'id_destinasi'      => 'required|integer',
            'jumlah_tiket'      => 'required|integer',
            'tanggal_berangkat' => 'required|date',
        ]);

        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;

        $orderId     = 'TRX-' . time() . rand(100, 999);
        $grossAmount = (int) $request->total;

        // ✅ TIDAK ada Pemesanan::create() di sini
        // Insert dilakukan oleh Flutter setelah pembayaran sukses

        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email'      => $request->email,
            ],
            'enabled_payments' => ['gopay'],
            'callbacks' => [
                // ✅ Custom scheme agar Flutter bisa tangkap langsung
                'finish' => 'myapp://payment-success',
                'error'  => 'myapp://payment-error',
                'cancel' => 'myapp://payment-error',
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);

            return response()->json([
                'snap_token'   => $snapToken,
                'order_id'     => $orderId,
                'redirect_url' => 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('MIDTRANS CREATE TRANSACTION ERROR', [
                'message' => $e->getMessage()
            ]);
            return response()->json([
                'message' => 'Gagal membuat transaksi: ' . $e->getMessage()
            ], 500);
        }
    }

    // ✅ Callback dari Midtrans server (bukan dari Flutter)
    // Hanya untuk update status — TIDAK insert data baru
    public function callback(Request $request)
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');

        Log::info('MIDTRANS CALLBACK MASUK');

        try {
            $data = json_decode($request->getContent(), true);

            if (!$data) {
                $data = $request->all();
            }

            $transaction = $data['transaction_status'] ?? null;
            $orderId     = $data['order_id'] ?? null;

            Log::info('MIDTRANS CALLBACK DATA', [
                'order_id' => $orderId,
                'status'   => $transaction,
            ]);

            if (!$orderId || !$transaction) {
                return response()->json(['message' => 'Data tidak lengkap'], 400);
            }

            $statusMap = [
                'settlement' => ['status' => 'success',  'midtrans_status' => 'settlement'],
                'capture'    => ['status' => 'success',  'midtrans_status' => 'capture'],
                'pending'    => ['status' => 'pending',  'midtrans_status' => 'pending'],
                'expire'     => ['status' => 'expire',   'midtrans_status' => 'expire'],
                'cancel'     => ['status' => 'cancel',   'midtrans_status' => 'cancel'],
                'deny'       => ['status' => 'cancel',   'midtrans_status' => 'deny'],
            ];

            if (isset($statusMap[$transaction])) {
                $updated = Pemesanan::where('order_id', $orderId)
                    ->update($statusMap[$transaction]);

                Log::info('PEMESANAN UPDATED', [
                    'order_id' => $orderId,
                    'status'   => $transaction,
                    'rows'     => $updated,
                ]);
            }

            return response()->json(['status' => 'ok'], 200);

        } catch (\Exception $e) {
            Log::error('MIDTRANS CALLBACK ERROR', ['message' => $e->getMessage()]);
            return response()->json(['message' => 'error'], 500);
        }
    }

    // ✅ Endpoint finish dari browser (opsional, bisa diabaikan)
    public function success(Request $request)
    {
        Log::info('PAYMENT SUCCESS PAGE', $request->all());
        return response()->json([
            'message' => 'Payment Success',
            'data'    => $request->all(),
        ]);
    }

    public function checkStatus($orderId)
{
    Config::$serverKey    = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');

    try {
        $status = \Midtrans\Transaction::status($orderId);
        return response()->json([
            'transaction_status' => $status->transaction_status,
            'order_id'           => $status->order_id,
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => $e->getMessage()], 500);
    }
}
}