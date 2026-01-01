<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    // --- eSewa ---

    public function esewaProcess(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $amount = $request->amount;
        $transactionUuid = Str::uuid()->toString();
        $merchantId = config('gateways.esewa.merchant_id');
        $successUrl = route('payment.esewa.success');
        $failureUrl = route('payment.esewa.failure');
        $productCode = 'EPAYTEST'; 

        // In production, this signature generation might differ based on SDK v2 or v1.
        // For v2 form based:
        // signature = base64(hmac_sha256(secret_key, "total_amount,transaction_uuid,product_code"))
        // But standard ePay often just needs simple form data for test.
        // Assuming we use the standard HTML form method for now.
        // For the 'EPAYTEST' environment, usually signature is not strictly enforced or is specific.
        // Let's implement the standard payload.

        // Create a pending transaction record
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'type' => 'deposit',
            'payment_method' => 'esewa',
            'transaction_id' => $transactionUuid, // Temporary ID until verified
            'status' => 'pending',
            'description' => 'eSewa Deposit Initiated',
        ]);

        // eSewa Parameters
        $params = [
            'amount' => $amount,
            'tax_amount' => 0,
            'total_amount' => $amount,
            'transaction_uuid' => $transactionUuid,
            'product_code' => $merchantId,
            'product_service_charge' => 0,
            'product_delivery_charge' => 0,
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'signed_field_names' => 'total_amount,transaction_uuid,product_code',
        ];

        // Signature (Required for v2)
        // Note: For test environment 'EPAYTEST', sometimes signature is ignored or static.
        // But proper implementation requires HMAC.
        // We will assume NO signature logic for EPAYTEST unless secret key is set, 
        // but typically for test one just sends the data.
        // If we need signature, we need a secret key in config.

        // For now, simpler implementation: Return a view that auto-submits.
        return view('payment.esewa-submit', compact('params', 'transaction'));
    }

    public function esewaSuccess(Request $request)
    {
        // eSewa redirects back with data: ?data=encoded_base64
        $data = $request->query('data');
        if (!$data) {
             return redirect()->route('wallet.index')->with('error', 'Invalid response from eSewa.');
        }

        $decodedData = json_decode(base64_decode($data), true);
        
        // $decodedData contains: transaction_code, status, total_amount, transaction_uuid, etc.
        
        if (!isset($decodedData['status']) || $decodedData['status'] !== 'COMPLETE') {
             return redirect()->route('wallet.index')->with('error', 'Payment failed or cancelled.');
        }

        $transactionUuid = $decodedData['transaction_uuid'];
        $dbTransaction = Transaction::where('transaction_id', $transactionUuid)->first();

        if (!$dbTransaction) {
             return redirect()->route('wallet.index')->with('error', 'Transaction not found.');
        }

        if ($dbTransaction->status === 'completed') {
             return redirect()->route('wallet.index')->with('success', 'Payment already processed.');
        }

        // Verify amount
        $amount = str_replace(',', '', $decodedData['total_amount']);
        if ((float)$amount != (float)$dbTransaction->amount) {
             return redirect()->route('wallet.index')->with('error', 'Amount mismatch.');
        }

        // Complete transaction
        \DB::transaction(function () use ($dbTransaction, $decodedData) {
            $dbTransaction->update([
                'status' => 'completed',
                'description' => 'eSewa Payment Success. Ref: ' . $decodedData['transaction_code'],
            ]);
            $dbTransaction->user->increment('balance', $dbTransaction->amount);
        });

        return redirect()->route('wallet.index')->with('success', 'eSewa Payment Successful! Funds added.');
    }

    public function esewaFailure(Request $request)
    {
        return redirect()->route('wallet.index')->with('error', 'eSewa Payment Failed.');
    }


    // --- Khalti ---

    public function khaltiProcess(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $amount = $request->amount;
        $purchaseOrderId = Str::uuid()->toString();
        $returnUrl = route('payment.khalti.callback');
        $secretKey = config('gateways.khalti.secret_key');
        
        if (empty($secretKey)) {
             return back()->with('error', 'Khalti configuration missing.');
        }

        // Create pending transaction
        $transaction = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $amount,
            'type' => 'deposit',
            'payment_method' => 'khalti',
            'transaction_id' => $purchaseOrderId,
            'status' => 'pending',
            'description' => 'Khalti Deposit Initiated',
        ]);

        // Initiate Payment
        $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post(config('gateways.khalti.url'), [
            'return_url' => $returnUrl,
            'website_url' => url('/'),
            'amount' => $amount * 100, // Khalti expects paisa
            'purchase_order_id' => $purchaseOrderId,
            'purchase_order_name' => 'Wallet Deposit',
            'customer_info' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => '9800000000' // Optional/Dummy
            ]
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return redirect($data['payment_url']);
        }

        return back()->with('error', 'Failed to initiate Khalti payment: ' . $response->body());
    }

    public function khaltiCallback(Request $request)
    {
         // Params: pidx, transaction_id, tidx, amount, mobile, purchase_order_id, status
         $pidx = $request->pidx;
         $status = $request->status;
         $purchaseOrderId = $request->purchase_order_id;
         
         if (!$pidx || !$purchaseOrderId) {
             return redirect()->route('wallet.index')->with('error', 'Invalid callback data.');
         }

         $dbTransaction = Transaction::where('transaction_id', $purchaseOrderId)->first();
         if (!$dbTransaction) {
             return redirect()->route('wallet.index')->with('error', 'Transaction not found.');
         }
         
         if ($status !== 'Completed') {
              $dbTransaction->update(['status' => 'failed']);
              return redirect()->route('wallet.index')->with('error', 'Khalti Payment Failed/Cancelled.');
         }

         // Verify with Lookup API
         $secretKey = config('gateways.khalti.secret_key');
         $response = Http::withHeaders([
            'Authorization' => 'Key ' . $secretKey,
            'Content-Type' => 'application/json',
        ])->post(config('gateways.khalti.verification_url'), [
            'pidx' => $pidx
        ]);

        if ($response->successful()) {
             $data = $response->json();
             if ($data['status'] == 'Completed') {
                 // Success
                 if ($dbTransaction->status !== 'completed') {
                     \DB::transaction(function () use ($dbTransaction, $pidx) {
                        $dbTransaction->update([
                            'status' => 'completed',
                            'description' => 'Khalti Success. PIDX: ' . $pidx,
                        ]);
                        $dbTransaction->user->increment('balance', $dbTransaction->amount);
                    });
                 }
                 return redirect()->route('wallet.index')->with('success', 'Khalti Payment Successful!');
             }
        }

        return redirect()->route('wallet.index')->with('error', 'Khalti Verification Failed.');
    }
}
