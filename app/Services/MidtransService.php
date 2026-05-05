<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

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
            return Snap::getStatus($orderId);
        } catch (\Exception $e) {
            \Log::error('Midtrans status error: ' . $e->getMessage());
            return null;
        }
    }
}