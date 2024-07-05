<?php

namespace App\Services\Midtrans;

use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;

class CreateSnapTokenService
{
    public function getSnapToken($payload)
    {
        // Set your Merchant Server Key
        Config::$serverKey = 'SB-Mid-server-g4gqpCKNyzmojclGDPOnsW9-';
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        Config::$isProduction = false;
        // Set sanitization on (default)
        Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        Config::$is3ds = true;

        // Generate a unique order ID
        $payload['transaction_details']['order_id'] = $this->generateUniqueOrderId();

        $snapToken = Snap::getSnapToken($payload);

        return $snapToken;
    }

    private function generateUniqueOrderId()
    {
        // You can implement your own logic here to generate a unique order ID
        // This is a simple example using timestamp
        return 'ORDER-' . time();
    }
}
