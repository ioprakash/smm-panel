<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Provider API Configurations
        Schema::create('smm_providers', function (Blueprint $table) {
            $table->id();
            $table->string('domain'); // e.g., jap.com
            $table->string('url')->nullable(); // Full API URL
            $table->string('api_key');
            $table->decimal('balance', 15, 4)->default(0);
            $table->string('currency')->default('USD');
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Service Categories
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Services
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->default('default'); // default, package, custom_comments
            
            // Pricing
            $table->decimal('price', 15, 4); // Price per 1000
            $table->integer('min_quantity')->default(100);
            $table->integer('max_quantity')->default(10000);
            
            // Provider Mapping
            $table->foreignId('smm_provider_id')->nullable()->constrained()->nullOnDelete();
            $table->string('provider_service_id')->nullable(); // ID on the provider's system
            $table->decimal('provider_rate', 15, 4)->nullable(); // Cost price
            
            $table->boolean('is_active')->default(true);
            $table->boolean('drip_feed_active')->default(false);
            $table->boolean('refill_available')->default(false);
            $table->timestamps();
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('service_id')->constrained();
            $table->text('link');
            $table->integer('quantity');
            $table->decimal('charge', 15, 4); // Total cost deducted
            $table->integer('start_count')->nullable();
            $table->integer('remains')->nullable();
            
            $table->string('status')->default('pending'); // pending, processing, completed, partial, canceled, refunded
            $table->text('api_response')->nullable(); // Debug info
            
            // Provider Info
            $table->foreignId('smm_provider_id')->nullable()->constrained();
            $table->string('provider_order_id')->nullable(); 
            
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('services');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('smm_providers');
    }
};
