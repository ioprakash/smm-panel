<x-admin-layout>
    <x-slot name="header">Ticket #{{ $ticket->id }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Messages Area -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Ticket Info -->
            <div class="card p-4 bg-indigo-50 border-indigo-100 mb-4">
                <h3 class="font-bold text-lg mb-1">{{ $ticket->subject }}</h3>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span>User: <strong>{{ $ticket->user->name }}</strong></span>
                    <span>Status: <strong class="uppercase">{{ $ticket->status }}</strong></span>
                    <span>Priority: <strong class="uppercase">{{ $ticket->priority }}</strong></span>
                </div>
            </div>

            <!-- Messages -->
            <div class="space-y-4">
                @foreach($ticket->messages as $message)
                    <div class="flex {{ $message->is_admin ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-lg {{ $message->is_admin ? 'bg-indigo-600 text-white' : 'bg-white border border-gray-200' }} rounded-lg p-4 shadow-sm">
                            <div class="flex items-center gap-2 mb-1 text-xs {{ $message->is_admin ? 'text-indigo-100' : 'text-gray-500' }}">
                                <span class="font-bold">{{ $message->is_admin ? 'Support Agent' : $message->user->name }}</span>
                                <span>•</span>
                                <span>{{ $message->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="text-sm whitespace-pre-wrap">{{ $message->message }}</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Reply Form -->
            @if($ticket->status !== 'closed')
                <div class="card mt-6">
                    <form action="{{ route('admin.tickets.reply', $ticket) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Reply</label>
                            <textarea name="message" rows="4" class="input-field" placeholder="Type your reply..." required></textarea>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="submit" class="btn-primary">Send Reply</button>
                        </div>
                    </form>
                </div>
            @else
                <div class="card p-6 text-center text-gray-500 bg-gray-50">
                    This ticket is closed.
                </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <div class="card">
                <h3 class="font-bold text-gray-900 mb-4">Actions</h3>
                @if($ticket->status !== 'closed')
                    <form action="{{ route('admin.tickets.close', $ticket) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full btn-secondary text-red-600 border-red-200 hover:bg-red-50">Mark as Closed</button>
                    </form>
                @else
                     <button disabled class="w-full btn-secondary opacity-50 cursor-not-allowed">Ticket Closed</button>
                @endif
            </div>

            <div class="card">
                <h3 class="font-bold text-gray-900 mb-2">User Details</h3>
                <div class="text-sm space-y-2">
                    <p class="text-gray-500">Name: <span class="text-gray-900 font-medium">{{ $ticket->user->name }}</span></p>
                    <p class="text-gray-500">Email: <span class="text-gray-900 font-medium">{{ $ticket->user->email }}</span></p>
                    <p class="text-gray-500">Balance: <span class="text-gray-900 font-medium">NPR {{ number_format($ticket->user->balance, 2) }}</span></p>
                    <a href="{{ route('admin.users.edit', $ticket->user) }}" class="text-indigo-600 hover:underline text-xs">View User Profile</a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
