<x-admin-layout>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-xs font-semibold bg-green-100 text-green-700 px-2 py-1 rounded">+12%</span>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Revenue</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">NPR {{ number_format($stats['revenue'], 2) }}</p>
        </div>

        <!-- Orders Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['orders']) }}</p>
        </div>

        <!-- Users Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Active Users</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['users']) }}</p>
        </div>

        <!-- Pending Orders Card -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                @if($stats['orders_pending'] > 0)
                    <span class="text-xs font-semibold bg-orange-100 text-orange-700 px-2 py-1 rounded">Action Needed</span>
                @endif
            </div>
            <h3 class="text-gray-500 text-sm font-medium">Pending Orders</h3>
            <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['orders_pending']) }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Chart Placeholder -->
        <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-900 mb-6">Revenue Overview</h3>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-200">
                <span class="text-gray-400 text-sm">ChartJS / ApexCharts Placeholder</span>
            </div>
        </div>

        <!-- Quick Actions & Status -->
        <div class="space-y-6">
            <!-- Shortcuts -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Quick Shortcuts</h3>
                <div class="grid grid-cols-2 gap-3">
                    <a href="{{ route('admin.users.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        Add User
                    </a>
                    <a href="{{ route('admin.services.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        New Service
                    </a>
                    <a href="{{ route('admin.transactions.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        View Logs
                    </a>
                    <a href="{{ route('admin.settings.index') }}" class="block p-3 text-sm font-medium text-slate-700 bg-slate-50 hover:bg-slate-100 rounded-lg border border-slate-200 text-center transition">
                        Settings
                    </a>
                </div>
            </div>

            <!-- Provider Health -->
             <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="font-bold text-gray-900 mb-4">Provider Status</h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-green-500"></span>
                            <span class="text-sm font-medium text-gray-700">JAP Provider</span>
                        </div>
                        <span class="text-xs text-green-600 font-semibold bg-green-50 px-2 py-0.5 rounded">Operational</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>
