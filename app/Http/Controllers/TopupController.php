<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TopupController extends Controller
{
    public function create()
    {
        return view('user.topup.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:10',
        ]);

        $trx = Transaction::create([
            'user_id' => auth()->id(),
            'amount' => $request->amount,
            'type' => 'deposit',
            'status' => 'pending',
            'transaction_id' => 'TRX-' . strtoupper(Str::random(8)), // Temporary ref
            'description' => 'Deposit Funds',
            'is_manual' => true,
        ]);

        return redirect()->route('user.topup.show', $trx->id);
    }

    public function show(Transaction $transaction)
    {
        // Ensure user owns this transaction
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        return view('user.topup.invoice', compact('transaction'));
    }

    public function update(Request $request, Transaction $transaction)
    {
        if ($transaction->user_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'transaction_id' => 'required|string|max:50',
            'payment_method' => 'nullable|string|in:esewa,khalti,bank,manual',
        ]);

        $transaction->update([
            'transaction_id' => $request->transaction_id, // User provided ref
            'payment_method' => $request->payment_method ?? 'manual',
            'status' => 'review', // Mark for admin review
            'description' => 'User submitted payment ref: ' . $request->transaction_id,
        ]);

        return back()->with('success', 'Payment submitted! Admin will verify shortly. If delayed, contact WhatsApp: 9843652752.');
    }
}
