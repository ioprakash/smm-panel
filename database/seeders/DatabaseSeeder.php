<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Service;
use App\Models\SmmProvider;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Users
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@smmpanel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'balance' => 100000,
        ]);

        $testUser = User::create([
            'name' => 'Test Customer',
            'email' => 'user@smmpanel.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'balance' => 500.00, // NPR
        ]);

        // 2. Create Providers
        $jap = SmmProvider::create([
            'domain' => 'justanotherpanel.com',
            'url' => 'https://justanotherpanel.com/api/v2',
            'api_key' => 'test_key_123',
            'balance' => 50.00,
            'currency' => 'USD'
        ]);

        // 3. Create Categories & Services
        $catInsta = Category::create(['name' => 'Instagram Likes', 'slug' => 'instagram-likes', 'sort_order' => 1]);
        $catYt = Category::create(['name' => 'YouTube Views', 'slug' => 'youtube-views', 'sort_order' => 2]);

        Service::create([
            'category_id' => $catInsta->id,
            'name' => 'Instagram Likes [Instant] [Max 10K]',
            'type' => 'default',
            'price' => 50.00, // NPR per 1000
            'min_quantity' => 100,
            'max_quantity' => 10000,
            'smm_provider_id' => $jap->id,
            'provider_service_id' => '102',
            'provider_rate' => 0.10, // USD
        ]);

        Service::create([
            'category_id' => $catInsta->id,
            'name' => 'Instagram Likes [Real Users]',
            'type' => 'default',
            'price' => 120.00, // NPR per 1000
            'min_quantity' => 50,
            'max_quantity' => 5000,
        ]);

        Service::create([
            'category_id' => $catYt->id,
            'name' => 'YouTube Views [Non-Drop]',
            'type' => 'default',
            'price' => 250.00, // NPR per 1000
            'min_quantity' => 500,
            'max_quantity' => 100000,
        ]);
        
        // 4. Create some transactions
        $testUser->transactions()->create([
            'amount' => 500,
            'type' => 'deposit',
            'payment_method' => 'manual',
            'status' => 'completed',
            'description' => 'Initial Bonus'
        ]);
    }
}
