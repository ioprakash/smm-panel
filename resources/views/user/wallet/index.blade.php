<x-app-layout>
    <x-slot name="header">Add Funds</x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        
        <!-- Payment Methods -->
        <div class="space-y-6">
            
            <!-- Esewa (Nepal) -->
            <div class="card hover:border-green-400 transition cursor-pointer relative overflow-hidden group">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-green-50 rounded-xl flex items-center justify-center text-green-600 font-bold text-xs border border-green-100">
                        eSewa
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Pay with eSewa</h3>
                        <p class="text-xs text-gray-500">Instant • No Fees</p>
                    </div>
                    <div class="ml-auto">
                        <span class="badge bg-green-100 text-green-700">Recommended</span>
                    </div>
                </div>
                
                <form action="{{ route('wallet.deposit') }}" method="POST" class="mt-6">
                    @csrf
                    <input type="hidden" name="gateway" value="esewa">
                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Amount (NPR)</label>
                    <div class="flex gap-2">
                        <input type="number" name="amount" class="input-field" placeholder="1000" min="10" required>
                        <button type="submit" class="btn-primary bg-green-600 hover:bg-green-700">Pay</button>
                    </div>
                </form>
            </div>

            <!-- Khalti (Mock) -->
            <div class="card hover:border-purple-400 transition cursor-pointer relative opacity-80">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 font-bold text-xs border border-purple-100">
                        Khalti
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Pay with Khalti</h3>
                        <p class="text-xs text-gray-500">Coming Soon</p>
                    </div>
                </div>
            </div>

             <!-- Bank Transfer -->
             <div class="card hover:border-blue-400 transition cursor-pointer">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-16 h-16 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 font-bold text-xs border border-blue-100">
                        BANK
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900">Bank Transfer</h3>
                        <p class="text-xs text-gray-500">Manual Verification</p>
                    </div>
                </div>
                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 mb-4">
                    <p><strong>Bank:</strong> Nabil Bank Ltd</p>
                    <p><strong>Acct Name:</strong> SMM Services Pvt Ltd</p>
                    <p><strong>Acct No:</strong> 012301230123</p>
                </div>
                <p class="text-xs text-gray-400 text-center">Please contact support with receipt after transfer.</p>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="card h-full">
            <h3 class="font-bold text-lg text-gray-900 mb-6">Recent Transactions</h3>
            <div class="flow-root">
                <ul class="-my-4 divide-y divide-gray-100">
                    @forelse(auth()->user()->transactions()->latest()->take(5)->get() as $txn)
                    <li class="py-4 flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-full {{ $txn->type == 'deposit' ? 'bg-green-50 text-green-600' : 'bg-red-50 text-red-600' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $txn->type == 'deposit' ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($txn->type) }}</p>
                                <p class="text-xs text-gray-500">{{ $txn->created_at->format('M d, H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900 {{ $txn->type == 'deposit' ? 'text-green-600' : '' }}">
                                {{ $txn->type == 'deposit' ? '+' : '-' }} NPR {{ number_format($txn->amount, 2) }}
                            </p>
                            <span class="badge {{ $txn->status == 'completed' ? 'bg-gray-100 text-gray-600' : 'bg-yellow-50 text-yellow-600' }}">
                                {{ ucfirst($txn->status) }}
                            </span>
                        </div>
                    </li>
                    @empty
                    <li class="py-8 text-center text-gray-400 text-sm">No transactions yet.</li>
                    @endforelse
                </ul>
            </div>
        </div>

    </div>
</x-app-layout>
