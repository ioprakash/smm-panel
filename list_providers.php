<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$providers = \App\Models\SmmProvider::all();
foreach ($providers as $provider) {
    echo "ID: {$provider->id} | Domain: {$provider->domain} | Active: {$provider->is_active} | URL: {$provider->url}\n";
}
