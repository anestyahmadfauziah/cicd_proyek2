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
            'total' => 'required|numeric|min:1',
            'name' => 'required|string',
            'email' => 'required|email',
        ]);

        // Config Midtrans
        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'TRX-' . time() . '-' . rand(1000,9999);
        $grossAmount = (int) $request->total;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
            ],

            // 🔥 HANYA GOPAY
            'enabled_payments' => ['gopay'],

            // callback setelah selesai
            'callbacks' => [
                'finish' => 'https://tia-pillowy-unpositively.ngrok-free.dev/payment/success'
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'snap_token' => $snapToken,
            'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/"
                . $snapToken
                . "?finish_redirect_url=https://tia-pillowy-unpositively.ngrok-free.dev/payment/success"
        ]);
    }

    public function success(Request $request)
    {
        return response()->json([
            'message' => 'Payment Success',
            'data' => $request->all()
        ]);
    }

    public function callback(Request $request)
{
    Config::$serverKey = config('services.midtrans.server_key');
    Config::$isProduction = config('services.midtrans.is_production');

    Log::info('CALLBACK MASUK');

    try {

        $data = json_decode($request->getContent(), true);

        if (!$data) {
            $data = $request->all();
        }

        $transaction = $data['transaction_status'] ?? null;
        $order_id = $data['order_id'] ?? null;

        Log::info('MIDTRANS CALLBACK', [
            'order_id' => $order_id,
            'status' => $transaction
        ]);

        // 🔥 UPDATE DATABASE
        if ($transaction === 'settlement') {

            Pemesanan::where('order_id', $order_id)
                ->update([
                    'status' => 'success',
                    'midtrans_status' => 'settlement'
                ]);

        } elseif ($transaction === 'pending') {

            Pemesanan::where('order_id', $order_id)
                ->update([
                    'status' => 'pending',
                    'midtrans_status' => 'pending'
                ]);

        } elseif ($transaction === 'expire') {

            Pemesanan::where('order_id', $order_id)
                ->update([
                    'status' => 'expire',
                    'midtrans_status' => 'expire'
                ]);

        } elseif ($transaction === 'cancel') {

            Pemesanan::where('order_id', $order_id)
                ->update([
                    'status' => 'cancel',
                    'midtrans_status' => 'cancel'
                ]);

        }

        return response()->json(['status' => 'ok'], 200);

    } catch (\Exception $e) {

        Log::error('MIDTRANS ERROR', [
            'message' => $e->getMessage()
        ]);

        return response()->json([
            'message' => 'error'
        ], 500);
    }
}
}