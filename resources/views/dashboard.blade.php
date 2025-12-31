<x-app-layout>
    <x-slot name="header">New Order</x-slot>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Total Orders -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-indigo-50 text-indigo-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Orders</p>
                <p class="text-xl font-bold text-gray-900">{{ number_format(auth()->user()->orders()->count()) }}</p>
            </div>
        </div>

        <!-- Balance -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-green-50 text-green-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</p>
                <p class="text-xl font-bold text-gray-900">NPR {{ number_format(auth()->user()->balance, 2) }}</p>
            </div>
        </div>

        <!-- Spent -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-Purple-50 text-purple-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</p>
                <p class="text-xl font-bold text-gray-900">NPR {{ number_format(auth()->user()->transactions()->where('type', 'spend')->sum('amount'), 2) }}</p>
            </div>
        </div>

        <!-- Status -->
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex items-center">
            <div class="p-3 rounded-lg bg-orange-50 text-orange-600 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <div>
                <p class="text-xs font-medium text-gray-500 uppercase tracking-wider">Status</p>
                <p class="text-xl font-bold text-gray-900 uppercase">New</p>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" 
         x-data="orderForm({{ json_encode($categories) }})">
        
        <!-- Left: New Order Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-lg text-gray-900">New Order</h3>
                    <div class="text-sm">
                        <span class="text-gray-500">Service Type:</span>
                        <span class="font-bold text-indigo-600 ml-1" x-text="selectedService ? selectedService.category.name : 'Select Category'"></span>
                    </div>
                </div>
                
                <div class="p-6">
                    <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <div class="relative">
                                <select x-model="selectedCategoryId" @change="updateServices" class="input-field appearance-none cursor-pointer">
                                    <option value="">Select a Category</option>
                                    <template x-for="category in categories" :key="category.id">
                                        <option :value="category.id" x-text="category.name"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Service -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Service</label>
                            <div class="relative">
                                <select name="service_id" x-model="selectedServiceId" @change="updateServiceDetails" class="input-field appearance-none cursor-pointer" :disabled="!selectedCategoryId">
                                    <option value="">Select a Service</option>
                                    <template x-for="service in availableServices" :key="service.id">
                                        <option :value="service.id" x-text="service.name + ' - NPR ' + parseFloat(service.price).toFixed(2)"></option>
                                    </template>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <!-- Link -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Link</label>
                            <input type="url" name="link" class="input-field" placeholder="https://..." required>
                        </div>

                        <!-- Quantity -->
                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                <input type="number" name="quantity" x-model.number="quantity" @input="calculateCharge" class="input-field" placeholder="0" required>
                                <div class="mt-1 text-xs text-gray-500" x-show="selectedService">
                                    Min: <span x-text="selectedService?.min_quantity"></span> | Max: <span x-text="selectedService?.max_quantity"></span>
                                </div>
                            </div>
                            
                            <!-- Calculated Charge -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Total Charge</label>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg px-4 py-3 text-gray-900 font-bold flex justify-between items-center">
                                    <span>NPR</span>
                                    <span x-text="charge">0.00</span>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="w-full btn-primary py-4 text-lg font-bold shadow-lg shadow-indigo-200">Submit Order</button>
                    </form>
                </div>
                
                <!-- Service Description Box -->
                <div class="bg-blue-50/50 p-6 border-t border-gray-100" x-show="selectedService">
                    <h4 class="font-bold text-gray-900 mb-2 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Service Description
                    </h4>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p><strong>Service ID:</strong> <span x-text="selectedService?.id"></span></p>
                        <p><strong>Speed:</strong> Instant (Estimated)</p>
                        <p><strong>Guarantee:</strong> No Refill</p>
                        <p class="mt-2 text-xs text-gray-500">Please make sure your account is public before ordering.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: News, Support, Recent -->
        <div class="space-y-6">
            <!-- News / Updates -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="bg-gray-50 px-4 py-3 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-sm">Latest News</h3>
                    <span class="text-xs bg-red-100 text-red-600 px-2 py-0.5 rounded-full font-bold">New</span>
                </div>
                <div class="p-4 space-y-4 max-h-64 overflow-y-auto custom-scrollbar">
                    <div class="flex gap-3">
                        <div class="mt-1 w-2 h-2 rounded-full bg-indigo-500 shrink-0"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">TikTok Services Updated</p>
                            <p class="text-xs text-gray-500 mt-1">We have updated prices for all TikTok views and likes. Check them out!</p>
                            <p class="text-[10px] text-gray-400 mt-1">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="mt-1 w-2 h-2 rounded-full bg-green-500 shrink-0"></div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">eSewa Payments Active</p>
                            <p class="text-xs text-gray-500 mt-1">Automatic eSewa deposits are now working perfectly.</p>
                            <p class="text-[10px] text-gray-400 mt-1">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Orders Compact -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="font-bold text-gray-900 text-sm">Recent Orders</h3>
                    <a href="{{ route('orders.history') }}" class="text-xs text-indigo-600 font-bold hover:underline">View All</a>
                </div>
                <div class="divide-y divide-gray-50">
                    @forelse(auth()->user()->orders()->latest()->take(5)->get() as $order)
                        <div class="p-3 flex justify-between items-center hover:bg-gray-50 transition">
                            <div>
                                <p class="text-xs font-bold text-gray-900 truncate max-w-[150px]">{{ $order->service->name }}</p>
                                <p class="text-[10px] text-gray-500">ID: {{ $order->id }} • {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-xs font-bold text-indigo-600">{{ number_format($order->charge, 2) }}</p>
                                <span class="text-[10px] uppercase font-bold text-{{ $order->status == 'completed' ? 'green' : 'blue' }}-500">{{ $order->status }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-gray-500">No recent orders</div>
                    @endforelse
                </div>
            </div>

            <!-- Support -->
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl shadow-lg p-6 text-white text-center">
                <h3 class="font-bold text-lg mb-1">Need Help?</h3>
                <p class="text-indigo-100 text-xs mb-4">Our support team is online 24/7.</p>
                <a href="{{ route('tickets.create') }}" class="inline-block bg-white text-indigo-600 px-6 py-2 rounded-lg font-bold text-sm hover:bg-gray-50 transition">Contact Support</a>
            </div>
        </div>
    </div>

    <!-- Alpine Logic -->
    <script>
        function orderForm(categoriesData) {
            return {
                categories: categoriesData,
                selectedCategoryId: '',
                selectedServiceId: '',
                availableServices: [],
                selectedService: null,
                quantity: '',
                charge: '0.00',

                init() {
                    // Pre-select if needed? No.
                },

                updateServices() {
                    this.selectedServiceId = '';
                    this.selectedService = null;
                    if (!this.selectedCategoryId) {
                        this.availableServices = [];
                        return;
                    }
                    const cat = this.categories.find(c => c.id == this.selectedCategoryId);
                    this.availableServices = cat ? cat.services : [];
                },

                updateServiceDetails() {
                    if (!this.selectedServiceId) {
                        this.selectedService = null;
                        this.calculateCharge();
                        return;
                    }
                    this.selectedService = this.availableServices.find(s => s.id == this.selectedServiceId);
                    // Add category ref if needed for display
                    if(this.selectedService) {
                         const cat = this.categories.find(c => c.id == this.selectedCategoryId);
                         this.selectedService.category = cat;
                    }
                    this.calculateCharge();
                },

                calculateCharge() {
                    if (!this.selectedService || !this.quantity) {
                        this.charge = '0.00';
                        return;
                    }
                    const price = parseFloat(this.selectedService.price);
                    const qty = parseInt(this.quantity);
                    if (isNaN(price) || isNaN(qty)) {
                        this.charge = '0.00';
                        return;
                    }
                    const total = (price * qty) / 1000;
                    this.charge = total.toFixed(2);
                }
            }
        }
    </script>
</x-app-layout>
