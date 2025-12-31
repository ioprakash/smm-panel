<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Services\Payment\EsewaGateway;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index()
    {
        return view('user.wallet.index');
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
            'gateway' => 'required|in:esewa,khalti,imepay'
        ]);

        $amount = $request->amount;
        $gateway = $request->gateway;
        $transactionId = uniqid('txn_');

        // Logic for Esewa
        if ($gateway === 'esewa') {
            $esewa = new EsewaGateway();
            $data = $esewa->initiate($amount, $transactionId);
            
            return view('user.wallet.redirect', ['data' => $data]);
        }

        return back()->with('error', 'Gateway not implemented yet.');
    }

    public function success(Request $request, $gateway)
    {
        if ($gateway === 'esewa') {
            $esewa = new EsewaGateway();
            $result = $esewa->verify($request);

            if ($result['status']) {
                $user = auth()->user();
                
                // Idempotency check: Check if transaction already processed
                // In real app, check DB for 'transaction_id' = $result['transaction_id']

                $user->increment('balance', $result['amount']);
                
                $user->transactions()->create([
                    'amount' => $result['amount'],
                    'type' => 'deposit',
                    'payment_method' => 'esewa',
                    'transaction_id' => $result['transaction_id'],
                    'status' => 'completed',
                    'description' => 'Deposit via eSewa'
                ]);

                return redirect()->route('wallet.index')->with('success', 'Deposit successful!');
            }
        }

        return redirect()->route('wallet.index')->with('error', 'Payment verification failed.');
    }
}
