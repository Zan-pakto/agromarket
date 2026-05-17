@extends('layouts.app')

@section('title', 'AgroMarket Store - Seed and Tool Catalogue')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Title -->
        <div class="mb-10 text-left">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">AgroMarket Marketplace</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Browse and filter premium certified products direct from original suppliers.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 items-start">
            
            <!-- Sidebar Advanced Filters -->
            <aside class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-8 lg:sticky lg:top-24">
                <div class="flex justify-between items-center pb-4 border-b dark:border-slate-700/60">
                    <h2 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Advanced Filters</h2>
                    <a href="{{ route('shop.index') }}" class="text-[10px] font-bold text-slate-400 hover:text-emerald-500 transition-colors">Reset All</a>
                </div>

                <form action="{{ route('shop.index') }}" method="GET" class="space-y-6">
                    <!-- Carry forward search -->
                    @if(request('search'))
                        <input type="hidden" name="search" value="{{ request('search') }}">
                    @endif

                    <!-- Category Selector -->
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Category</label>
                        <div class="space-y-2 max-h-48 overflow-y-auto pr-2">
                            @foreach($categories as $category)
                                <label class="flex items-center space-x-2.5 text-xs text-slate-600 dark:text-slate-300 font-semibold cursor-pointer hover:text-emerald-500 transition-colors">
                                    <input type="radio" name="category" value="{{ $category->id }}" 
                                           class="text-emerald-500 focus:ring-emerald-500 border-slate-300 rounded"
                                           {{ request('category') == $category->id ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span>{{ $category->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Selector -->
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Price Range ($)</label>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" 
                                   class="text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white">
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" 
                                   class="text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white">
                        </div>
                    </div>

                    <!-- Ratings Selector -->
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Minimum Rating</label>
                        <div class="space-y-2">
                            @foreach([4, 3, 2] as $stars)
                                <label class="flex items-center space-x-2 text-xs font-semibold text-slate-600 dark:text-slate-300 cursor-pointer hover:text-emerald-500 transition-colors">
                                    <input type="radio" name="rating" value="{{ $stars }}" 
                                           class="text-emerald-500 focus:ring-emerald-500 border-slate-300 rounded"
                                           {{ request('rating') == $stars ? 'checked' : '' }}
                                           onchange="this.form.submit()">
                                    <span class="flex text-amber-500 text-[10px] space-x-0.5">
                                        @for ($i = 0; $i < 5; $i++)
                                            <i class="{{ $i < $stars ? 'fa-solid fa-star' : 'fa-regular fa-star text-slate-300 dark:text-slate-600' }}"></i>
                                        @endfor
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-bold">& Up</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Sorting Carrier -->
                    @if(request('sort'))
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                    @endif

                    <button type="submit" 
                            class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 active:scale-98 text-white rounded-xl font-bold text-xs uppercase tracking-wider shadow-md transition-all">
                        Apply Filters
                    </button>
                </form>
            </aside>

            <!-- Product Showcase Catalog Grid -->
            <div class="lg:col-span-3 space-y-8">
                <!-- Sorting & Metrics Header -->
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm px-6 py-4">
                    <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                        Showing <span class="text-slate-800 dark:text-white font-bold">{{ $products->firstItem() ?? 0 }}</span> - 
                        <span class="text-slate-800 dark:text-white font-bold">{{ $products->lastItem() ?? 0 }}</span> of 
                        <span class="text-slate-800 dark:text-white font-bold">{{ $products->total() }}</span> products
                    </p>

                    <form action="{{ route('shop.index') }}" method="GET" class="flex items-center space-x-2">
                        <!-- carry forward current filters -->
                        @foreach(request()->except('sort') as $key => $val)
                            @if(is_array($val))
                                @foreach($val as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endif
                        @endforeach

                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-wider">Sort By:</label>
                        <select name="sort" onchange="this.form.submit()" 
                                class="text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrival</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular & Top Rated</option>
                        </select>
                    </form>
                </div>

                @if($products->count() > 0)
                    <!-- Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($products as $product)
                            <x-product-card :product="$product" />
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="pt-6">
                        {{ $products->links() }}
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-slate-800 rounded-[40px] border border-slate-100 dark:border-slate-700/60 shadow-sm space-y-6 text-center">
                        <div class="bg-amber-100 dark:bg-amber-950/20 text-amber-500 p-6 rounded-full shadow-md">
                            <i class="fa-solid fa-seedling text-3xl"></i>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-base font-bold text-slate-800 dark:text-white">No products found matching filters</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">Try widening your price range, choosing another category, or resetting all search options.</p>
                        </div>
                        <a href="{{ route('shop.index') }}" 
                           class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-bold text-xs uppercase rounded-xl shadow-lg transition-transform hover:scale-105 active:scale-95">
                            Show All Products
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
