<x-admin-layout>
    <x-slot name="header">Support Tickets</x-slot>

    <div class="card">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-4 py-3">ID</th>
                        <th class="px-4 py-3">Subject</th>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Priority</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3">Last Update</th>
                        <th class="px-4 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">#{{ $ticket->id }}</td>
                            <td class="px-4 py-3">{{ $ticket->subject }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold">
                                        {{ substr($ticket->user->name, 0, 1) }}
                                    </div>
                                    {{ $ticket->user->name }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $ticket->priority === 'high' ? 'bg-red-100 text-red-800' : 
                                      ($ticket->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs font-bold 
                                    {{ $ticket->status === 'open' ? 'bg-green-100 text-green-800' : 
                                      ($ticket->status === 'closed' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-xs">{{ $ticket->updated_at->diffForHumans() }}</td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.tickets.show', $ticket) }}" class="btn-primary py-1 px-3 text-xs">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-gray-500">No tickets found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    </div>
</x-admin-layout>
