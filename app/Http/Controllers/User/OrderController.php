<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Service;
use App\Services\Smm\JapLikeProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $categories = Category::with(['services' => function($q) {
            $q->where('is_active', true);
        }])->where('is_active', true)->orderBy('sort_order')->get();

        return view('user.orders.new', compact('categories'));
    }

    public function history()
    {
        $orders = auth()->user()->orders()->with('service')->latest()->paginate(20);
        return view('user.orders.history', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'link' => 'required|url',
            'quantity' => 'required|integer|min:1',
        ]);

        $service = Service::findOrFail($request->service_id);
        
        if ($request->quantity < $service->min_quantity || $request->quantity > $service->max_quantity) {
            return back()->withErrors(['quantity' => "Quantity must be between {$service->min_quantity} and {$service->max_quantity}"]);
        }

        $totalPrice = ($service->price / 1000) * $request->quantity;
        $user = auth()->user();

        if ($user->balance < $totalPrice) {
            return back()->withErrors(['balance' => 'Insufficient balance. Please deposit funds.']);
        }

        // DB Transaction to ensure atomicity
        try {
            DB::beginTransaction();

            // Deduct Balance
            $user->decrement('balance', $totalPrice);

            // Create Transaction Log
            $user->transactions()->create([
                'amount' => $totalPrice,
                'type' => 'spend',
                'description' => "Order #{$service->id} - {$service->name}",
                'status' => 'completed'
            ]);

            // Create Order
            $order = $user->orders()->create([
                'service_id' => $service->id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'charge' => $totalPrice,
                'status' => 'pending',
            ]);

            // If Service has a connected provider, send to API
            if ($service->smm_provider_id && $service->provider_service_id) {
                // This would ideally be a Job, but doing inline for MVP
                try {
                    $provider = $service->provider;
                    $api = new JapLikeProvider($provider->url, $provider->api_key);
                    $apiRes = $api->addOrder($service->provider_service_id, $request->link, $request->quantity);
                    
                    $order->update([
                        'provider_order_id' => $apiRes['order_id'] ?? null,
                        'smm_provider_id' => $provider->id
                    ]);
                } catch (\Exception $e) {
                    // Log error but don't fail the user's order
                    \Log::error("API Order Failed: " . $e->getMessage());
                }
            }

            DB::commit();

            return redirect()->route('orders.history')->with('success', 'Order placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()]);
        }
    }
}
