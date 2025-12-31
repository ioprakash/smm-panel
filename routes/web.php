<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\WalletController;
use App\Http\Controllers\User\TicketController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use Illuminate\Support\Facades\Route;
use App\Models\Category;

use App\Models\BlogPost;

Route::get('/', function () {
    $categories = Category::with(['services' => function($q) {
        $q->where('is_active', true)->limit(5); // Limit for preview
    }])->where('is_active', true)->orderBy('sort_order')->get();
    
    $latest_posts = BlogPost::where('is_published', true)->latest()->get();
    
    return view('welcome', compact('categories', 'latest_posts'));
});

Route::get('/services', [\App\Http\Controllers\Guest\ServiceController::class, 'index'])->name('guest.services');

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard (User)
    Route::get('/dashboard', function () {
        $categories = Category::with(['services' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->orderBy('sort_order')->get();
        return view('dashboard', compact('categories'));
    })->name('dashboard');

    // Orders
    Route::get('/orders/new', [OrderController::class, 'index'])->name('orders.new');
    Route::post('/orders/store', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

    // Wallet
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/deposit', [WalletController::class, 'deposit'])->name('wallet.deposit');
    Route::get('/payment/success/{gateway}', [WalletController::class, 'success'])->name('payment.success');
    Route::get('/payment/failure/{gateway}', [WalletController::class, 'failure'])->name('payment.failure');

    // Tickets
    Route::resource('tickets', TicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('/tickets/{ticket}/reply', [TicketController::class, 'reply'])->name('tickets.reply');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/users/{user}/login', [\App\Http\Controllers\Admin\UserController::class, 'loginAsUser'])->name('users.login');
    Route::patch('/users/{user}/ban', [\App\Http\Controllers\Admin\UserController::class, 'toggleBan'])->name('users.ban');
    Route::get('/users/{user}/edit', [\App\Http\Controllers\Admin\UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
    Route::get('/services', [\App\Http\Controllers\Admin\ServiceController::class, 'index'])->name('services.index');
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::resource('providers', \App\Http\Controllers\Admin\ProviderController::class);
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionController::class, 'index'])->name('transactions.index');
    
    // Tickets
    Route::controller(\App\Http\Controllers\Admin\TicketController::class)->prefix('tickets')->name('tickets.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{ticket}', 'show')->name('show');
        Route::post('/{ticket}/reply', 'reply')->name('reply');
        Route::patch('/{ticket}/close', 'close')->name('close');
    });

    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'store'])->name('settings.store');

    // Blogs
    Route::resource('blogs', \App\Http\Controllers\Admin\BlogController::class);
});

require __DIR__.'/auth.php';
