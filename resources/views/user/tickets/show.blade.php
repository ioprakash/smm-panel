<x-app-layout>
    <x-slot name="header">Ticket #{{ $ticket->id }}</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 h-[calc(100vh-10rem)]">
        
        <!-- Chat Area -->
        <div class="lg:col-span-2 flex flex-col h-full card p-0 overflow-hidden">
            <!-- Header -->
            <div class="p-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                <div>
                    <h2 class="font-bold text-gray-900">{{ $ticket->subject }}</h2>
                    <p class="text-xs text-gray-500">Started {{ $ticket->created_at->format('M d, Y') }}</p>
                </div>
                <span class="badge {{ $ticket->status == 'open' ? 'bg-green-100 text-green-700' : 'bg-gray-100' }}">
                    {{ ucfirst($ticket->status) }}
                </span>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-white min-h-[400px]">
                @foreach($ticket->messages as $msg)
                    <div class="flex {{ $msg->is_admin ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[80%]">
                            <div class="flex items-center gap-2 mb-1 {{ $msg->is_admin ? 'justify-start' : 'justify-end' }}">
                                <span class="text-xs font-bold text-gray-700">{{ $msg->is_admin ? 'Support Agent' : 'You' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $msg->created_at->format('H:i') }}</span>
                            </div>
                            <div class="p-4 rounded-2xl shadow-sm text-sm {{ $msg->is_admin ? 'bg-gray-100 text-gray-800 rounded-tl-none' : 'bg-indigo-600 text-white rounded-tr-none' }}">
                                {!! nl2br(e($msg->message)) !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Input Area -->
            @if($ticket->status !== 'closed')
                <div class="p-4 bg-white border-t border-gray-100">
                    <form action="{{ route('tickets.reply', $ticket) }}" method="POST" class="flex gap-4">
                        @csrf
                        <input type="text" name="message" class="flex-1 input-field" placeholder="Type your reply..." required autocomplete="off">
                        <button type="submit" class="btn-primary px-6">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            @else
                <div class="p-4 bg-gray-50 text-center text-sm text-gray-500 border-t border-gray-100">
                    This ticket has been closed. Please open a new ticket for further assistance.
                </div>
            @endif
        </div>

        <!-- Info Sidebar -->
        <div class="space-y-6">
            <div class="card bg-gray-50 border-gray-200">
                <h3 class="font-bold text-gray-900 mb-4">Ticket Info</h3>
                <dl class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Ticket ID</dt>
                        <dd class="font-medium">#{{ $ticket->id }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Priority</dt>
                        <dd class="font-medium capitalize">{{ $ticket->priority }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-gray-500">Last Update</dt>
                        <dd class="font-medium">{{ $ticket->updated_at->diffForHumans() }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</x-app-layout>
