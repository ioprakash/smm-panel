<?php

namespace App\Services\Smm;

use Illuminate\Support\Facades\Http;
use Exception;

class JapLikeProvider implements ProviderInterface
{
    protected string $apiUrl;
    protected string $apiKey;

    public function __construct(string $url, string $key)
    {
        $this->apiUrl = $url;
        $this->apiKey = $key;
    }

    protected function request(array $params)
    {
        $params['key'] = $this->apiKey;
        $response = Http::asForm()->post($this->apiUrl, $params);
        
        if ($response->failed()) {
            throw new Exception("Provider API Error: " . $response->body());
        }

        return $response->json();
    }

    public function getBalance(): float
    {
        $data = $this->request(['action' => 'balance']);
        return (float) ($data['balance'] ?? 0);
    }

    public function services(): array
    {
        return $this->request(['action' => 'services']);
    }

    public function addOrder(int $serviceId, string $link, int $quantity): array
    {
        $data = $this->request([
            'action' => 'add',
            'service' => $serviceId,
            'link' => $link,
            'quantity' => $quantity
        ]);

        if (isset($data['order'])) {
            return ['order_id' => $data['order']];
        }

        throw new Exception("Order Failed: " . json_encode($data));
    }

    public function getOrderStatus(int $orderId): array
    {
        $data = $this->request([
            'action' => 'status',
            'order' => $orderId
        ]);

        return [
            'status' => $data['status'] ?? 'unknown',
            'remains' => $data['remains'] ?? 0,
            'start_count' => $data['start_count'] ?? 0,
            'charge' => $data['charge'] ?? 0,
        ];
    }
}
