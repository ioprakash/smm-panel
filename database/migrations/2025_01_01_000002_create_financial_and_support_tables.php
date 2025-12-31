<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Wallet Transactions
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->decimal('amount', 15, 4);
            $table->string('type'); // deposit, spend, refund, transfer
            $table->string('payment_method')->nullable(); // esewa, khalti, manual, system
            $table->string('transaction_id')->nullable(); // Gateway Transaction ID
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->text('description')->nullable();
            $table->boolean('is_manual')->default(false);
            $table->string('proof_image')->nullable(); // For manual deposits
            $table->timestamps();
        });

        // Tickets (Support)
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('subject');
            $table->string('status')->default('open'); // open, answered, closed
            $table->string('priority')->default('medium');
            $table->timestamps();
        });

        Schema::create('ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained(); // Sender
            $table->text('message');
            $table->boolean('is_admin')->default(false);
            $table->timestamps();
        });
        
        // Add Balance & Role to Users Table
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('balance', 15, 4)->default(0)->after('email');
            $table->string('role')->default('user')->after('balance'); // user, reseller, admin
            $table->string('api_key')->nullable()->unique()->after('password');
            $table->string('timezone')->default('Asia/Kathmandu');
            $table->boolean('is_banned')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['balance', 'role', 'api_key', 'timezone', 'is_banned']);
        });
        Schema::dropIfExists('ticket_messages');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('transactions');
    }
};
