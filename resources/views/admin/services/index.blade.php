<x-admin-layout>
    <x-slot name="header">Services</x-slot>
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Services Management</h2>
        <div class="flex gap-3">
             <button class="bg-white text-gray-700 border border-gray-300 px-4 py-2 rounded-lg hover:bg-gray-50 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Import
            </button>
            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Service
            </button>
        </div>
    </div>

    <!-- Category Tabs (UI only mostly) -->
    <div class="flex overflow-x-auto pb-4 gap-2 mb-4 no-scrollbar">
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-full text-sm font-semibold whitespace-nowrap shadow-md shadow-indigo-200">All Services</button>
        @foreach($categories as $category)
            <button class="px-4 py-2 bg-white text-gray-600 border border-gray-200 hover:bg-gray-50 rounded-full text-sm font-medium whitespace-nowrap transition">
                {{ $category->name }}
            </button>
        @endforeach
    </div>

    <!-- Services Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 font-semibold w-16">ID</th>
                        <th class="px-6 py-4 font-semibold">Service Name</th>
                        <th class="px-6 py-4 font-semibold">Category</th>
                        <th class="px-6 py-4 font-semibold text-right">Price (NPR)</th>
                        <th class="px-6 py-4 font-semibold text-center">Min / Max</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($services as $service)
                    <tr class="hover:bg-gray-50 transition group">
                        <td class="px-6 py-4 text-gray-400 font-mono text-xs">{{ $service->id }}</td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $service->name }}</div>
                            <div class="text-xs text-gray-400">Provider ID: {{ $service->provider_service_id ?? 'N/A' }}</div>
                        </td>
                         <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-800">
                                {{ $service->category->name ?? 'Uncategorized' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-800">
                            {{ number_format($service->price, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center text-xs text-gray-500">
                            {{ $service->min_quantity }} - {{ $service->max_quantity }}
                        </td>
                         <td class="px-6 py-4 text-center">
                             <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" value="" class="sr-only peer" {{ $service->is_active ? 'checked' : '' }}>
                                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </td>
                        <td class="px-6 py-4 text-right opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="text-blue-600 hover:text-blue-900 mx-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                            </button>
                            <button class="text-red-600 hover:text-red-900 mx-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-10 text-center text-gray-500">No services found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-gray-100">
             {{ $services->links() }}
        </div>
    </div>
</x-admin-layout>
