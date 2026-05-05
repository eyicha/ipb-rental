<?php
// app/Http/Controllers/MidtransController.php
namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function webhook(Request $request)
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        $notif       = new Notification();
        $status      = $notif->transaction_status;
        $type        = $notif->payment_type;
        $orderId     = $notif->order_id;
        $fraudStatus = $notif->fraud_status;

        $transaction = Transaction::where('order_id', $orderId)->first();
        if (!$transaction) return response()->json(['message' => 'Not found'], 404);

        // Mapping status Midtrans
        if ($status == 'capture') {
            $finalStatus = ($fraudStatus == 'accept') ? 'settlement' : 'pending';
        } elseif ($status == 'settlement') {
            $finalStatus = 'settlement';
        } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
            $finalStatus = $status;
        } else {
            $finalStatus = 'pending';
        }

        $transaction->update([
            'status'             => $finalStatus,
            'payment_type'       => $type,
            'midtrans_response'  => $notif->getResponse(),
        ]);

        return response()->json(['message' => 'OK']);
    }
}