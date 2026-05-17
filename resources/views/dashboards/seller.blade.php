@extends('layouts.app')

@section('title', 'Seller Storefront Panel - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen" x-data="{ currentTab: 'overview', editMode: false, editProduct: {} }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Banner -->
        <div class="bg-gradient-to-br from-slate-900 to-emerald-950 rounded-[40px] shadow-2xl p-8 sm:p-12 text-white mb-12 relative overflow-hidden text-left">
            <div class="absolute -top-12 -left-12 w-64 h-64 bg-emerald-500/5 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-emerald-500/5 rounded-full blur-xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-emerald-500 text-white p-3.5 rounded-2xl shadow-lg">
                        <i class="fa-solid fa-store text-xl"></i>
                    </div>
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-md bg-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider">Merchant Portal</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">{{ Auth::user()->name }}</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Supplier Store | Approved & Verified</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytical Counters -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12 text-left">
            <!-- Counter 1 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Store Sales</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">${{ number_format($analytics['total_sales'], 2) }}</p>
                <span class="text-[9px] text-emerald-500 font-bold block"><i class="fa-solid fa-arrow-trend-up mr-1"></i> Lifetime Earnings</span>
            </div>

            <!-- Counter 2 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Orders Placed</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $analytics['total_orders'] }}</p>
                <span class="text-[9px] text-slate-400 font-bold block">Items ordered by farmers</span>
            </div>

            <!-- Counter 3 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Products Listed</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $products->total() }}</p>
                <span class="text-[9px] text-emerald-500 font-bold block">Active catalog items</span>
            </div>

            <!-- Counter 4 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Low Stock Alert</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $analytics['low_stock_count'] }}</p>
                <span class="text-[9px] text-red-500 font-bold block"><i class="fa-solid fa-circle-exclamation mr-1"></i> Needs replenishment</span>
            </div>
        </div>

        <!-- Primary Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Side Navigation Links -->
            <aside class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-4 flex flex-col space-y-1">
                <button @click="currentTab = 'overview'; editMode = false" 
                        :class="currentTab === 'overview' && !editMode ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    <span>Store Overview</span>
                </button>

                <button @click="currentTab = 'inventory'; editMode = false" 
                        :class="currentTab === 'inventory' && !editMode ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-warehouse w-5 text-center"></i>
                    <span>Manage Inventory</span>
                </button>

                <button @click="currentTab = 'publish'; editMode = false" 
                        :class="currentTab === 'publish' && !editMode ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-circle-plus w-5 text-center"></i>
                    <span>Publish Seed/Tool</span>
                </button>

                <button @click="currentTab = 'orders'; editMode = false" 
                        :class="currentTab === 'orders' && !editMode ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-truck-ramp-box w-5 text-center"></i>
                    <span>Incoming Orders</span>
                </button>
            </aside>

            <!-- Main Interactive Containers -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- Tab Overview: Chart.js visualization -->
                <div x-show="currentTab === 'overview' && !editMode" class="space-y-8">
                    <!-- Chart canvas -->
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 text-left space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Monthly Revenues Performance</h3>
                        <div class="relative h-72 w-full">
                            <canvas id="sellerSalesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tab Publish Product: Form details -->
                <div x-show="currentTab === 'publish' && !editMode" class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6 text-left" style="display: none;">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Publish New Seed or Tool Listing</h3>
                    
                    <form action="{{ route('dashboard.seller.product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product Title</label>
                                <input type="text" name="name" required placeholder="e.g. Organic Beefsteak Tomato Seeds (200 Seeds)"
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>
                            
                            <!-- Category Dropdown -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Marketplace Category Division</label>
                                <select name="category_id" required 
                                        class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Retail Pricing ($)</label>
                                <input type="number" step="0.01" name="price" required placeholder="e.g. 12.99"
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>

                            <!-- Inventory stock -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initial Inventory Stock Units</label>
                                <input type="number" name="quantity" required placeholder="e.g. 150"
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Detailed Product Information</label>
                            <textarea name="description" rows="4" required placeholder="Describe germination rates, packaging size, crop guidelines, warranty..."
                                      class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold"></textarea>
                        </div>

                        <!-- Images upload -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product Catalog Photos (Multi-Select Allowed)</label>
                            <input type="file" name="images[]" required multiple accept="image/*"
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-500 file:text-white file:cursor-pointer hover:file:bg-emerald-600 transition-all">
                        </div>

                        <button type="submit" 
                                class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 active:scale-95 transition-all">
                            Publish Listing Active
                        </button>
                    </form>
                </div>

                <!-- Tab Inventory: list products -->
                <div x-show="currentTab === 'inventory' && !editMode" class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden" style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                <th class="px-6 py-4">Item Details</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4 text-right">Pricing</th>
                                <th class="px-6 py-4 text-center">In Stock</th>
                                <th class="px-6 py-4 text-center">Avg rating</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                            @forelse($products as $prod)
                                <tr>
                                    <!-- details -->
                                    <td class="px-6 py-5 flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-slate-900 overflow-hidden shrink-0 border dark:border-slate-700">
                                            <img src="{{ $prod->getFirstImage() }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="text-left max-w-xs">
                                            <h4 class="font-bold text-slate-800 dark:text-white truncate">{{ $prod->name }}</h4>
                                            <span class="text-[9px] text-slate-400 font-bold">Product ID: {{ $prod->id }}</span>
                                        </div>
                                    </td>
                                    <!-- Category -->
                                    <td class="px-6 py-5 font-semibold text-emerald-500 uppercase tracking-wider text-[10px]">
                                        {{ $prod->category->name ?? 'Agriculture' }}
                                    </td>
                                    <!-- Price -->
                                    <td class="px-6 py-5 text-right font-bold text-slate-900 dark:text-white">
                                        ${{ number_format($prod->price, 2) }}
                                    </td>
                                    <!-- Qty -->
                                    <td class="px-6 py-5 text-center font-extrabold">
                                        <span class="px-2.5 py-1 rounded-lg {{ $prod->quantity <= 0 ? 'bg-red-50 text-red-700 dark:bg-red-950/20 dark:text-red-400' : ($prod->quantity < 10 ? 'bg-amber-50 text-amber-700 dark:bg-amber-950/20 dark:text-amber-400' : 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/20 dark:text-emerald-400') }}">
                                            {{ $prod->quantity }} units
                                        </span>
                                    </td>
                                    <!-- rating -->
                                    <td class="px-6 py-5 text-center text-amber-500 font-bold">
                                        <i class="fa-solid fa-star text-[10px] mr-1"></i>{{ $prod->ratings_avg }}
                                    </td>
                                    <!-- actions -->
                                    <td class="px-6 py-5 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button @click="editMode = true; editProduct = { id: '{{ $prod->id }}', name: '{{ addslashes($prod->name) }}', price: '{{ $prod->price }}', quantity: '{{ $prod->quantity }}', category_id: '{{ $prod->category_id }}', description: '{{ addslashes(str_replace(array("\r", "\n"), ' ', $prod->description)) }}' }" 
                                                    class="p-2 hover:bg-emerald-50 dark:hover:bg-slate-950/20 text-emerald-500 rounded-xl transition-all" title="Edit Inventory Item">
                                                <i class="fa-solid fa-pen text-sm"></i>
                                            </button>
                                            
                                            <form action="{{ route('dashboard.seller.product.delete', $prod->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently remove this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 hover:bg-red-50 dark:hover:bg-red-950/20 text-red-500 rounded-xl transition-all" title="Delete Listing">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                        No active products in inventory. Publish your first item to start trading!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="p-4">
                        {{ $products->appends(['products_page' => $products->currentPage()])->links() }}
                    </div>
                </div>

                <!-- Tab Incoming Orders -->
                <div x-show="currentTab === 'orders' && !editMode" class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden" style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                <th class="px-6 py-4">Order Code</th>
                                <th class="px-6 py-4">Purchaser (Farmer)</th>
                                <th class="px-6 py-4">Placement Date</th>
                                <th class="px-6 py-4 text-center">Payment Info</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Adjust Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                            @forelse($orders as $ord)
                                <tr>
                                    <td class="px-6 py-5 font-bold text-slate-800 dark:text-white">
                                        <a href="{{ route('orders.show', $ord->id) }}" class="underline hover:text-emerald-500">{{ $ord->tracking_number }}</a>
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-600 dark:text-slate-300 text-left">
                                        <p class="font-bold">{{ $ord->billing_name }}</p>
                                        <p class="text-[9px] text-slate-400">{{ $ord->billing_phone }}</p>
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-400">
                                        {{ $ord->created_at ? $ord->created_at->format('M d, Y') : now()->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-5 text-center font-bold uppercase text-slate-600 dark:text-slate-300">
                                        {{ $ord->payment_method }} ({{ $ord->payment_status }})
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-lg border {{ $ord->getStatusColorClass() }}">
                                            {{ $ord->order_status }}
                                        </span>
                                    </td>
                                    <!-- adjust status form -->
                                    <td class="px-6 py-5">
                                        <form action="{{ route('dashboard.seller.order.status', $ord->id) }}" method="POST" class="flex items-center space-x-2">
                                            @csrf
                                            <select name="order_status" 
                                                    class="text-[10px] bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-2 py-1.5 focus:outline-none text-slate-800 dark:text-white font-semibold">
                                                <option value="confirmed" {{ $ord->order_status == 'confirmed' ? 'selected' : '' }}>Confirm</option>
                                                <option value="shipped" {{ $ord->order_status == 'shipped' ? 'selected' : '' }}>Ship</option>
                                                <option value="delivered" {{ $ord->order_status == 'delivered' ? 'selected' : '' }}>Deliver</option>
                                                <option value="cancelled" {{ $ord->order_status == 'cancelled' ? 'selected' : '' }}>Cancel</option>
                                            </select>
                                            
                                            <!-- Tracking input placeholder -->
                                            <input type="hidden" name="tracking_number" value="{{ $ord->tracking_number }}">
                                            
                                            <button type="submit" class="text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-450 p-1.5" title="Submit Shipment status update">
                                                <i class="fa-solid fa-circle-check text-sm"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                        No incoming customer orders recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Alpine.js Inline Edit Product Mode -->
                <div x-show="editMode" class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6 text-left" style="display: none;">
                    <div class="flex justify-between items-center pb-3 border-b dark:border-slate-700/60">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Edit Product: <span x-text="editProduct.name"></span></h3>
                        <button @click="editMode = false" class="text-slate-450 hover:text-slate-600"><i class="fa-solid fa-xmark text-lg"></i></button>
                    </div>

                    <form :action="'/dashboard/seller/product/' + editProduct.id" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Product Name -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product Title</label>
                                <input type="text" name="name" x-model="editProduct.name" required 
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>
                            
                            <!-- Category Dropdown -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Marketplace Category Division</label>
                                <select name="category_id" x-model="editProduct.category_id" required 
                                        class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Price -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Retail Pricing ($)</label>
                                <input type="number" step="0.01" name="price" x-model="editProduct.price" required 
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>

                            <!-- Inventory stock -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Inventory Stock Units</label>
                                <input type="number" name="quantity" x-model="editProduct.quantity" required 
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>
                        </div>

                        <!-- Product Description -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product Information</label>
                            <textarea name="description" rows="4" x-model="editProduct.description" required
                                      class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold"></textarea>
                        </div>

                        <!-- Images upload -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Replace Catalog Photos (Optional, Multi-Select Allowed)</label>
                            <input type="file" name="images[]" multiple accept="image/*"
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-400 file:mr-4 file:py-1.5 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-emerald-500 file:text-white file:cursor-pointer hover:file:bg-emerald-600 transition-all">
                        </div>

                        <div class="flex space-x-3 pt-3">
                            <button type="submit" 
                                    class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md transition-all">
                                Update Product Specifications
                            </button>
                            <button type="button" @click="editMode = false" 
                                    class="px-8 py-3.5 border border-slate-350 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 rounded-2xl font-black text-xs uppercase transition-all">
                                Cancel Updates
                            </button>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('sellerSalesChart');
        if (ctx) {
            // Retrieve Chart coordinates dynamically from PHP backend arrays
            const monthlyLabels = {!! json_encode(array_keys($analytics['monthly_sales'])) !!};
            const monthlyValues = {!! json_encode(array_values($analytics['monthly_sales'])) !!};

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Gross Sales ($)',
                        data: monthlyValues,
                        backgroundColor: '#10b981', // emerald-500
                        borderRadius: 12,
                        borderWidth: 0,
                        barThickness: 24,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
