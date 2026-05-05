<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
   public function checkout(\App\Models\Rental $rental, Request $request)
{
    $user = auth()->user();

    if ((string)$rental->penyewa_id !== (string)$user->_id) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    if ($rental->status !== 'pending_payment') {
        return response()->json(['error' => 'Status rental tidak valid: ' . $rental->status], 422);
    }

    $amount = (int) $rental->deposit;
    if ($amount <= 0) {
        return response()->json(['error' => 'Nominal deposit tidak valid: ' . $amount], 422);
    }

    // Reuse token yang masih fresh (< 23 jam)
    $existing = Transaction::where('rental_id', (string)$rental->_id)
        ->where('status', 'pending')
        ->where('created_at', '>=', now()->subHours(23))
        ->latest()->first();

    if ($existing) {
        return response()->json([
            'snap_token' => $existing->snap_token,
            'order_id'   => $existing->order_id,
            'client_key' => env('MIDTRANS_CLIENT_KEY'),
        ]);
    }

    $orderId = 'DP-' . time() . '-' . Str::random(5);

    $params = [
        'transaction_details' => [
            'order_id'     => $orderId,
            'gross_amount' => $amount,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email'      => $user->email,
        ],
        'item_details' => [[
            'id'       => (string) $rental->item_id,
            'price'    => $amount,
            'quantity' => 1,
            'name'     => substr('DP Sewa - ' . ($rental->item->nama ?? 'Item'), 0, 50),
        ]],
    ];

    try {
        $midtrans  = new MidtransService();
        $snapToken = $midtrans->createSnapToken($params);
    } catch (\Exception $e) {
        \Log::error('Midtrans createSnapToken error: ' . $e->getMessage());
        return response()->json(['error' => 'Gagal membuat token: ' . $e->getMessage()], 500);
    }

    Transaction::create([
        'user_id'      => (string) $user->_id,
        'item_id'      => (string) $rental->item_id,
        'rental_id'    => (string) $rental->_id,
        'order_id'     => $orderId,
        'gross_amount' => $amount,
        'snap_token'   => $snapToken,
        'status'       => 'pending',
    ]);

    return response()->json([
        'snap_token' => $snapToken,
        'order_id'   => $orderId,
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
    ]);
}
    public function checkStatus(\App\Models\Rental $rental)
    {
        try {
            if ($rental->penyewa_id !== auth()->id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Find transaction for this rental DP payment
            $payment = Transaction::where('item_id', $rental->item_id)
                ->where('user_id', auth()->id())
                ->where('created_at', '>=', $rental->created_at)
                ->latest()
                ->first();

            if (!$payment) {
                // If no transaction found but rental is active, payment was completed
                if ($rental->status === 'active') {
                    return response()->json([
                        'status' => 'success',
                        'order_id' => 'N/A',
                        'amount' => $rental->deposit,
                    ]);
                }
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Check payment status from Midtrans
            $midtrans = new MidtransService();
            $status = $midtrans->getTransactionStatus($payment->order_id);

            // Update transaction if status changed
            if ($status && isset($status['transaction_status'])) {
                $transactionStatus = $status['transaction_status'];
                
                if ($rental && $rental->status === 'pending_payment') {
    $rental->update(['status' => 'dp_paid']);
}
            }

            return response()->json([
                'status' => $payment->status,
                'order_id' => $payment->order_id,
                'amount' => $payment->gross_amount,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Mock payment for testing - mark rental as paid without actual Midtrans
     */
   public function mockPayment(\App\Models\Rental $rental)
{
    try {
        // Fix: cast ke string untuk compare MongoDB ObjectId
        if ((string)$rental->penyewa_id !== (string)auth()->id()) {
            return back()->with('error', 'Unauthorized');
        }

        Transaction::create([
            'user_id'      => (string) auth()->id(),
            'item_id'      => (string) $rental->item_id,
            'rental_id'    => (string) $rental->_id,
            'order_id'     => 'MOCK-' . time() . '-' . \Illuminate\Support\Str::random(5),
            'gross_amount' => $rental->deposit,
            'status'       => 'success',
        ]);

        // Fix: status harusnya dp_paid dulu, bukan langsung active
        $rental->update(['status' => 'dp_paid']);

        return back()->with('success', 'Pembayaran DP berhasil! Menunggu barang diantarkan.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error: ' . $e->getMessage());
    }
}

public function paySuccess(\App\Models\Rental $rental, Request $request)
{
    Transaction::where('order_id', $request->order_id)
        ->update(['status' => 'success']);

    $rental->update(['status' => 'dp_paid']);

    return response()->json(['message' => 'OK']);
}

    /**
     * Midtrans webhook callback
     */
    public function callback(Request $request)
    {
        try {
            $orderId = $request->order_id;
            $statusCode = $request->status_code;
            $grossAmount = $request->gross_amount;
            $signatureKey = $request->signature_key;

            // Verify signature
            $serverKey = env('MIDTRANS_SERVER_KEY');
            $signature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($signature !== $signatureKey) {
                \Log::warning('Invalid Midtrans signature');
                return response('Invalid signature', 403);
            }

            // Update transaction status
            $transaction = Transaction::where('order_id', $orderId)->first();
            
            if ($transaction) {
                $transactionStatus = $request->transaction_status;
                
                if ($transactionStatus === 'settlement') {
                    $transaction->update(['status' => 'success']);
                    
                    // Update related rental to active
                    $rental = \App\Models\Rental::where('item_id', $transaction->item_id)
                        ->where('penyewa_id', $transaction->user_id)
                        ->latest()
                        ->first();
                    
                    if ($rental) {
                        $rental->update(['status' => 'active']);
                    }
                } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                    $transaction->update(['status' => 'failed']);
                }
            }

            return response('OK', 200);
        } catch (\Exception $e) {
            \Log::error('Midtrans callback error: ' . $e->getMessage());
            return response('Error', 500);
        }
    }
}