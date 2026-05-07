<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$clientKey    = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function createSnapToken(array $params): string
    {
        return Snap::getSnapToken($params);
    }

    public function getTransactionStatus(string $orderId): ?array
    {
        try {
            $status = Transaction::status($orderId);
            return (array) $status;
        } catch (\Exception $e) {
            \Log::error('Midtrans status error: ' . $e->getMessage());
            return null;
        }
    }
}