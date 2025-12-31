<x-app-layout>
    <x-slot name="header">New Order</x-slot>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8" x-data="orderForm()">
        
        <!-- Order Form -->
        <div class="lg:col-span-2">
            <div class="card relative overflow-hidden">
                <!-- Progress Indicator (Visual) -->
                <div class="absolute top-0 left-0 w-full h-1 bg-gray-100">
                    <div class="h-full bg-indigo-600 transition-all duration-500" style="width: 0%" :style="'width: ' + progress + '%'"></div>
                </div>

                <form action="{{ route('orders.store') }}" method="POST" class="space-y-6 mt-4">
                    @csrf
                    
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Platform / Category</label>
                        <select id="category" name="category_id" x-model="selectedCategory" @change="updateServices" class="input-field bg-gray-50">
                            <option value="">Select a Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Service -->
                    <div x-show="selectedCategory" x-transition.opacity>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Service</label>
                        <select id="service" name="service_id" x-model="selectedServiceId" @change="updateServiceDetails" class="input-field bg-gray-50">
                            <option value="">Select a Service</option>
                            <template x-for="service in services" :key="service.id">
                                <option :value="service.id" x-text="service.name + ' - NPR ' + service.price + '/1k'"></option>
                            </template>
                        </select>
                        <!-- Service Description Box -->
                        <div x-show="currentService" class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100 text-sm text-blue-800">
                            <p><strong>Min:</strong> <span x-text="currentService?.min"></span> | <strong>Max:</strong> <span x-text="currentService?.max"></span></p>
                            <p class="mt-1 text-xs text-blue-600" x-text="currentService?.desc || 'No description available for this service.'"></p>
                        </div>
                    </div>

                    <!-- Link -->
                    <div x-show="selectedServiceId" x-transition.opacity>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Link / Username</label>
                        <input type="url" name="link" class="input-field" placeholder="https://..." required>
                    </div>

                    <!-- Quantity -->
                    <div x-show="selectedServiceId" x-transition.opacity>
                        <div class="flex justify-between">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                            <span class="text-xs text-indigo-600 font-medium cursor-pointer" @click="quantity = currentService.min">Set Min</span>
                        </div>
                        <input type="number" name="quantity" x-model="quantity" class="input-field" placeholder="1000" required>
                    </div>

                    <!-- Submit -->
                    <div x-show="selectedServiceId" x-transition.opacity class="pt-4">
                        <button type="submit" class="w-full btn-primary py-3 text-base shadow-lg shadow-indigo-200">
                            Place Order <span x-show="totalPrice > 0"> (NPR <span x-text="totalPrice"></span>)</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Price Summary -->
            <div class="card bg-gray-900 text-white border-0">
                <h3 class="text-lg font-bold mb-4">Order Summary</h3>
                <div class="space-y-3 text-sm text-gray-400">
                    <div class="flex justify-between">
                        <span>Service Rate</span>
                        <span class="text-white font-medium" x-text="currentService ? 'NPR ' + currentService.price : '-'"></span>
                    </div>
                    <div class="flex justify-between">
                        <span>Quantity</span>
                        <span class="text-white font-medium" x-text="quantity || '-'"></span>
                    </div>
                    <div class="h-px bg-gray-700 my-2"></div>
                    <div class="flex justify-between text-lg font-bold text-white">
                        <span>Total Charge</span>
                        <span>NPR <span x-text="totalPrice">0.00</span></span>
                    </div>
                </div>
            </div>

            <!-- Important Info -->
            <div class="card bg-orange-50 border-orange-100">
                <h3 class="text-sm font-bold text-orange-800 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Important Rules
                </h3>
                <ul class="mt-3 space-y-2 text-xs text-orange-700 list-disc list-inside">
                    <li>Make sure your account is public.</li>
                    <li>Do not place duplicate orders for the same link.</li>
                    <li>Quantities must be multiples of 10 usually.</li>
                </ul>
            </div>
        </div>

    </div>

    <!-- Hidden data for Alpine -->
    <script>
        // Pre-render PHP data into JS variable
        const allServices = {
            @foreach($categories as $category)
                "{{ $category->id }}": [
                    @foreach($category->services as $service)
                    {
                        id: "{{ $service->id }}",
                        name: "{{ $service->name }}",
                        price: {{ $service->price }},
                        min: {{ $service->min_quantity }},
                        max: {{ $service->max_quantity }},
                        desc: "{{ $service->description ?? '' }}",
                    },
                    @endforeach
                ],
            @endforeach
        };

        function orderForm() {
            return {
                selectedCategory: '',
                selectedServiceId: '',
                services: [],
                currentService: null,
                quantity: '',
                
                updateServices() {
                    this.services = allServices[this.selectedCategory] || [];
                    this.selectedServiceId = '';
                    this.currentService = null;
                    this.quantity = '';
                },
                
                updateServiceDetails() {
                    this.currentService = this.services.find(s => s.id == this.selectedServiceId);
                    // Set default min quantity if service selected
                    if (this.currentService) {
                        this.quantity = this.currentService.min; 
                    }
                },

                get totalPrice() {
                    if (!this.currentService || !this.quantity) return '0.00';
                    return ((this.currentService.price / 1000) * this.quantity).toFixed(2);
                },

                get progress() {
                    let step = 0;
                    if (this.selectedCategory) step += 33;
                    if (this.selectedServiceId) step += 33;
                    if (this.quantity) step += 34;
                    return step;
                }
            }
        }
    </script>
</x-app-layout>
