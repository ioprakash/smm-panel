<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$provider = \App\Models\SmmProvider::find(13);
if ($provider) {
    echo "ID: {$provider->id}\n";
    echo "URL: {$provider->url}\n";
    echo "Key: {$provider->api_key}\n";
} else {
    echo "Provider 13 not found.\n";
}
