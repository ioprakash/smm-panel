<x-app-layout>
    <x-slot name="header">Open Ticket</x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="card">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Create New Ticket</h2>
            
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                    <select name="subject" class="input-field bg-gray-50">
                        <option>Order Issue</option>
                        <option>Payment/Deposit Problem</option>
                        <option>API Support</option>
                        <option>Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Priority</label>
                    <select name="priority" class="input-field bg-gray-50">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High (Urgent)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                    <textarea name="message" rows="6" class="input-field" placeholder="Please describe your issue in detail..." required></textarea>
                    <p class="text-xs text-gray-500 mt-2">If this is about an order, please include the Order ID.</p>
                </div>

                <div class="flex justify-end gap-4 pt-4">
                    <a href="{{ route('tickets.index') }}" class="btn-secondary">Cancel</a>
                    <button type="submit" class="btn-primary">Submit Ticket</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
