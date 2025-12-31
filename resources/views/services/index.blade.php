<x-guest-layout>
    <div class="bg-slate-900 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
            <h1 class="text-3xl font-extrabold sm:text-4xl">Our Services</h1>
            <p class="mt-4 text-xl text-slate-300">Detailed list of our high-quality social media services.</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ activeCategory: 'all' }">
        
        <!-- Category Filter -->
        <div class="flex overflow-x-auto pb-6 gap-3 mb-4 no-scrollbar">
            <button @click="activeCategory = 'all'" 
                :class="{ 'bg-indigo-600 text-white': activeCategory === 'all', 'bg-white text-gray-700 hover:bg-gray-50': activeCategory !== 'all' }"
                class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap shadow-sm transition-all">
                All Services
            </button>
            @foreach($categories as $category)
            <button @click="activeCategory = '{{ $category->id }}'" 
                :class="{ 'bg-indigo-600 text-white': activeCategory === '{{ $category->id }}', 'bg-white text-gray-700 hover:bg-gray-50': activeCategory !== '{{ $category->id }}' }"
                class="px-5 py-2.5 rounded-full text-sm font-semibold whitespace-nowrap shadow-sm transition-all border border-gray-100">
                {{ $category->name }}
            </button>
            @endforeach
        </div>

        <!-- Services Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-4 font-semibold w-20">ID</th>
                            <th class="px-6 py-4 font-semibold">Service</th>
                            <th class="px-6 py-4 font-semibold text-right">Rate per 1000</th>
                            <th class="px-6 py-4 font-semibold text-center">Min / Max</th>
                            <th class="px-6 py-4 font-semibold text-center">Description</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($services as $service)
                        <tr x-show="activeCategory === 'all' || activeCategory === '{{ $service->category_id }}'" class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-gray-400 font-mono">{{ $service->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 text-base">{{ $service->name }}</div>
                                <div class="text-xs text-indigo-500 mt-0.5">{{ $service->category->name }}</div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="bg-indigo-50 text-indigo-700 font-bold px-2.5 py-1 rounded-lg">
                                    NPR {{ number_format($service->price, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-mono text-gray-600">
                                {{ $service->min_quantity }} - {{ $service->max_quantity }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button class="text-gray-400 hover:text-indigo-600" title="View Details">
                                    <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-guest-layout>
