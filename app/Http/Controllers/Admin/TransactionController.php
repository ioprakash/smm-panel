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
}
