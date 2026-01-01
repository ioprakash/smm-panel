<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Funds') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Deposit Funds to Your Wallet</h3>
                    <p class="text-gray-500 mb-6 text-sm">Enter the amount you would like to deposit. You will be redirected to the payment invoice page for instructions.</p>

                    <form action="{{ route('user.topup.store') }}" method="POST">
                        @csrf
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Amount (NPR)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rs.</span>
                                </div>
                                <input type="number" name="amount" class="pl-10 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" placeholder="500" min="10" required>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-3 rounded-lg hover:bg-indigo-700 transition">
                            Create Invoice
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
