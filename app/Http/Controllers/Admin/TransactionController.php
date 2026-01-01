<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = \App\Models\Transaction::with('user')->latest()->paginate(20);
        $totalDeposits = \App\Models\Transaction::where('type', 'deposit')->where('status', 'completed')->sum('amount');
        $monthDeposits = \App\Models\Transaction::where('type', 'deposit')
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('amount');
            
        return view('admin.transactions.index', compact('transactions', 'totalDeposits', 'monthDeposits'));
    }

    public function approve(\App\Models\Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Transaction already completed');
        }

        \DB::transaction(function () use ($transaction) {
            $transaction->update(['status' => 'completed']);
            // Only add balance if it's a deposit
            if ($transaction->type === 'deposit') {
                $transaction->user->increment('balance', $transaction->amount);
            }
        });

        return back()->with('success', 'Transaction approved and funds added.');
    }

    public function reject(\App\Models\Transaction $transaction)
    {
        if ($transaction->status === 'completed') {
            return back()->with('error', 'Cannot reject a completed transaction');
        }

        $transaction->update(['status' => 'failed']);
        return back()->with('success', 'Transaction rejected.');
    }
}
