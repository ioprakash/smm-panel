<?php

namespace App\Services\Smm;

interface ProviderInterface
{
    public function getBalance(): float;
    
    public function services(): array;
    
    public function addOrder(int $serviceId, string $link, int $quantity): array; // returns ['order_id' => 123]
    
    public function getOrderStatus(int $orderId): array; // returns ['status' => 'Completed', 'remains' => 0]
}
