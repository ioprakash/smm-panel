<x-app-layout>
    <x-slot name="header">Support Tickets</x-slot>

    <div class="card">
        <div class="flex flex-col sm:flex-row justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900">Your Tickets</h3>
            <a href="{{ route('tickets.create') }}" class="btn-primary flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Open New Ticket
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-lg">Subject</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Priority</th>
                        <th class="px-6 py-4">Last Update</th>
                        <th class="px-6 py-4 text-right rounded-tr-lg">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($tickets as $ticket)
                        <tr class="bg-white hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">#{{ $ticket->id }} - {{ $ticket->subject }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColors = [
                                        'open' => 'bg-green-100 text-green-700',
                                        'answered' => 'bg-blue-100 text-blue-700',
                                        'closed' => 'bg-gray-100 text-gray-600',
                                    ];
                                @endphp
                                <span class="badge {{ $statusColors[$ticket->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $priorityColors = [
                                        'high' => 'bg-red-50 text-red-600',
                                        'medium' => 'bg-yellow-50 text-yellow-600',
                                        'low' => 'bg-gray-50 text-gray-600',
                                    ];
                                @endphp
                                <span class="badge {{ $priorityColors[$ticket->priority] ?? 'bg-gray-50 text-gray-600' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-xs">
                                {{ $ticket->updated_at->diffForHumans() }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('tickets.show', $ticket) }}" class="btn-secondary py-1 px-3 text-xs">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-gray-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                    <p>No support tickets yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
    </div>
</x-app-layout>
