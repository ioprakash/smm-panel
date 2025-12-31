<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiController extends Controller
{
    // Helper to validate API Key
    private function getUser(Request $request)
    {
        $key = $request->input('key');
        if (!$key) abort(401, 'API Key missing');

        $user = \App\Models\User::where('api_key', $key)->first();
        if (!$user) abort(401, 'Invalid API Key');
        
        return $user;
    }

    public function handle(Request $request)
    {
        $action = $request->input('action');

        switch ($action) {
            case 'services':
                return $this->services();
            case 'add':
                return $this->addOrder($request);
            case 'status':
                return $this->status($request);
            case 'balance':
                return $this->balance($request);
            default:
                return response()->json(['error' => 'Invalid action'], 400);
        }
    }

    public function services()
    {
        return Service::where('is_active', true)->get(['id as service', 'name', 'type', 'category_id as category', 'price as rate', 'min_quantity as min', 'max_quantity as max']);
    }

    public function addOrder(Request $request)
    {
        $user = $this->getUser($request);

        $validator = Validator::make($request->all(), [
            'service' => 'required|exists:services,id',
            'link' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 400);
        }

        $service = Service::find($request->service);
        $charge = ($service->price / 1000) * $request->quantity;

        if ($user->balance < $charge) {
            return response()->json(['error' => 'Not enough funds'], 400);
        }

        $user->decrement('balance', $charge);
        
        $order = $user->orders()->create([
            'service_id' => $service->id,
            'link' => $request->link,
            'quantity' => $request->quantity,
            'charge' => $charge,
            'status' => 'pending'
        ]);

        return response()->json(['order' => $order->id]);
    }

    public function status(Request $request)
    {
        $this->getUser($request);
        $order = Order::find($request->order);

        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        return response()->json([
            'charge' => $order->charge,
            'start_count' => $order->start_count,
            'status' => $order->status,
            'remains' => $order->remains,
            'currency' => 'NPR'
        ]);
    }

    public function balance(Request $request)
    {
        $user = $this->getUser($request);
        return response()->json([
            'balance' => $user->balance,
            'currency' => 'NPR'
        ]);
    }
}
