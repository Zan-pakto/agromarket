@extends('layouts.app')

@section('title', $product->name . ' - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen" x-data="{ currentImage: '{{ $product->getFirstImage() }}' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-400 mb-8">
            <a href="{{ url('/') }}" class="hover:text-emerald-500 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <a href="{{ route('shop.index') }}" class="hover:text-emerald-500 transition-colors">Shop</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <a href="{{ route('shop.index') }}?category={{ $product->category_id }}" class="hover:text-emerald-500 transition-colors">{{ $product->category->name ?? 'Agriculture' }}</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-slate-600 dark:text-slate-200 truncate">{{ $product->name }}</span>
        </nav>

        <!-- Product Presentation -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 bg-white dark:bg-slate-800 rounded-[40px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-12 mb-16">
            
            <!-- Left Column: Gallery -->
            <div class="lg:col-span-6 space-y-6">
                <!-- Large Active Image -->
                <div class="relative w-full aspect-video sm:aspect-square bg-slate-50 dark:bg-slate-900 rounded-3xl overflow-hidden border dark:border-slate-700">
                    <img :src="currentImage" class="w-full h-full object-cover transition-all duration-300" id="main-image">
                </div>

                <!-- Thumbnail gallery list -->
                @if(is_array($product->images) && count($product->images) > 1)
                    <div class="grid grid-cols-4 gap-4">
                        @foreach($product->images as $img)
                            <button @click="currentImage = '{{ $img }}'" 
                                    class="w-full aspect-square bg-slate-50 dark:bg-slate-900 rounded-xl overflow-hidden border-2 focus:outline-none transition-all duration-200"
                                    :class="currentImage === '{{ $img }}' ? 'border-emerald-500 ring-2 ring-emerald-500/20' : 'border-transparent hover:border-slate-200 dark:hover:border-slate-700'">
                                <img src="{{ $img }}" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Column: Details -->
            <div class="lg:col-span-6 flex flex-col justify-between space-y-6">
                <div class="space-y-4">
                    <!-- Category & Availability -->
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-emerald-500 uppercase tracking-widest">{{ $product->category->name ?? 'Agriculture' }}</span>
                        <!-- stock status -->
                        <span class="px-3 py-1 rounded-xl text-xs font-bold border {{ $product->quantity > 0 ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-400 dark:border-emerald-800' : 'bg-red-50 text-red-700 border-red-200 dark:bg-red-950/40 dark:text-red-400 dark:border-red-800' }}">
                            {{ $product->getStockStatus() }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">
                        {{ $product->name }}
                    </h1>

                    <!-- Verified supplier store link -->
                    <div class="flex items-center space-x-2 text-xs font-semibold text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900/50 p-3 rounded-2xl border dark:border-slate-700/60 max-w-sm">
                        <i class="fa-solid fa-store text-emerald-500 text-sm"></i>
                        <span>Sold & Fulfilled by: <strong class="text-slate-800 dark:text-white font-bold">{{ $product->seller->name ?? 'Verified Seller' }}</strong></span>
                    </div>

                    <!-- Stars summary -->
                    <div class="flex items-center space-x-3 text-xs">
                        <span class="flex text-amber-500 space-x-0.5">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= floor($product->ratings_avg))
                                    <i class="fa-solid fa-star"></i>
                                @elseif ($i - 0.5 <= $product->ratings_avg)
                                    <i class="fa-solid fa-star-half-stroke"></i>
                                @else
                                    <i class="fa-regular fa-star text-slate-300 dark:text-slate-600"></i>
                                @endif
                            @endfor
                        </span>
                        <span class="text-slate-700 dark:text-slate-300 font-bold">{{ $product->ratings_avg }} average rating</span>
                        <span class="text-slate-400 font-bold">({{ $product->ratings_count }} customer reviews)</span>
                    </div>

                    <!-- Pricing -->
                    <div class="py-4 border-t border-b dark:border-slate-700/60">
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-wider">Unit Price</p>
                        <p class="text-3xl font-black text-slate-900 dark:text-white mt-1">${{ number_format($product->price, 2) }}</p>
                    </div>

                    <!-- Description -->
                    <div class="space-y-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Product Description</h3>
                        <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                            {{ $product->description }}
                        </p>
                    </div>
                </div>

                <!-- Add to cart buy options -->
                <div class="pt-6 border-t dark:border-slate-700/60">
                    @if($product->quantity > 0)
                        <form action="{{ route('cart.add') }}" method="POST" class="flex flex-col sm:flex-row items-stretch gap-4 max-w-md">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            
                            <!-- Quantity selector -->
                            <div class="flex items-center bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 shrink-0" x-data="{ qty: 1 }">
                                <button type="button" @click="qty = Math.max(1, qty - 1)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                                    <i class="fa-solid fa-minus"></i>
                                </button>
                                <input type="number" name="quantity" x-model="qty" readonly 
                                       class="w-12 text-center bg-transparent border-none text-slate-800 dark:text-white text-sm font-bold focus:outline-none">
                                <button type="button" @click="qty = Math.min({{ $product->quantity }}, qty + 1)" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>

                            <!-- Buy button -->
                            <button type="submit" 
                                    class="flex-grow py-4 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 active:scale-98 transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-basket-shopping text-sm"></i>
                                <span>Add to Cart Basket</span>
                            </button>
                        </form>
                    @else
                        <button class="w-full py-4 bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 rounded-2xl font-black text-xs uppercase cursor-not-allowed flex items-center justify-center space-x-2" disabled>
                            <i class="fa-solid fa-ban"></i>
                            <span>Product Currently Out of Stock</span>
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related products and Customer Reviews -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start mb-16">
            
            <!-- Left side: Review module -->
            <section class="lg:col-span-8 bg-white dark:bg-slate-800 rounded-[40px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-12 space-y-10">
                <div class="space-y-4">
                    <h2 class="text-xl font-black text-slate-900 dark:text-white border-l-4 border-emerald-500 pl-3">Farmer Ratings & Reviews</h2>
                    <p class="text-xs text-slate-500 dark:text-slate-400">See what farmers say about this product, or leave your rating based on germination and results.</p>
                </div>

                <!-- Submit review block -->
                @auth
                    <div class="bg-emerald-50/20 dark:bg-slate-900/50 p-6 rounded-3xl border dark:border-slate-700/60 space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white">Write Your Review</h3>
                        <form action="{{ route('reviews.store', $product->id) }}" method="POST" class="space-y-4">
                            @csrf
                                
                                <!-- Stars select -->
                                <div class="flex items-center space-x-4" x-data="{ activeStar: 5 }">
                                    <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Rating Stars:</span>
                                    <div class="flex space-x-1.5 text-amber-500 text-lg cursor-pointer">
                                        @for($s = 1; $s <= 5; $s++)
                                            <input type="radio" name="rating" value="{{ $s }}" id="star-{{ $s }}" class="hidden" :checked="activeStar === {{ $s }}">
                                            <label @click="activeStar = {{ $s }}" for="star-{{ $s }}">
                                                <i class="fa-solid fa-star transition-all duration-150 hover:scale-125" :class="activeStar >= {{ $s }} ? 'text-amber-500' : 'text-slate-200 dark:text-slate-700'"></i>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review comment -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Feedback Comment</label>
                                    <textarea name="comment" rows="3" placeholder="Share your crop results, packaging quality, tool efficiency..." required
                                              class="w-full text-xs bg-white dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white placeholder-slate-400"></textarea>
                                </div>

                                <button type="submit" 
                                        class="px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-md transition-all active:scale-95">
                                    Submit Review
                                </button>
                            </form>
                        </div>
                @else
                    <div class="p-6 rounded-3xl bg-slate-50 dark:bg-slate-900/50 text-center border border-dashed dark:border-slate-700">
                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">
                            Please <a href="{{ route('login') }}" class="text-emerald-500 font-bold underline">Login</a> to rate and write reviews.
                        </p>
                    </div>
                @endauth

                <!-- Reviews lists -->
                <div class="space-y-6">
                    @forelse($product->reviews as $rev)
                        <div class="p-6 bg-slate-50/50 dark:bg-slate-900/30 rounded-3xl border border-slate-100 dark:border-slate-700/60 flex items-start space-x-4">
                            <img class="h-9 w-9 rounded-xl object-cover shrink-0 ring-2 ring-emerald-500/10" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode($rev->user->name ?? 'Farmer') }}&background=10b981&color=fff&bold=true" 
                                 alt="">
                            <div class="space-y-2 flex-grow text-left">
                                <div class="flex justify-between items-center">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">{{ $rev->user->name ?? 'Verified Farmer' }}</h4>
                                    <span class="text-[10px] text-slate-400 font-medium">{{ $rev->created_at ? $rev->created_at->diffForHumans() : 'Recently' }}</span>
                                </div>
                                <div class="flex text-amber-500 text-[10px]">
                                    @for ($s = 1; $s <= 5; $s++)
                                        <i class="fa-solid fa-star {{ $s <= $rev->rating ? 'text-amber-500' : 'text-slate-200 dark:text-slate-700' }}"></i>
                                    @endfor
                                </div>
                                <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                                    {{ $rev->comment }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <i class="fa-solid fa-comments text-slate-300 text-3xl mb-4"></i>
                            <p class="text-xs font-semibold text-slate-400">This seed or tool has no reviews yet. Be the first farmer to evaluate it!</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Right side: Sidebar (Related Products & Recently viewed) -->
            <aside class="lg:col-span-4 space-y-12">
                <!-- Related Products -->
                <div class="space-y-6">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3">Related Items</h3>
                    <div class="grid grid-cols-1 gap-6">
                        @forelse($relatedProducts as $rel)
                            <a href="{{ route('shop.show', $rel->id) }}" class="flex bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/60 p-3 shadow-sm hover:shadow-md transition-shadow group items-center space-x-3">
                                <div class="w-16 h-16 rounded-xl bg-slate-50 dark:bg-slate-900 overflow-hidden shrink-0">
                                    <img src="{{ $rel->getFirstImage() }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-grow text-left">
                                    <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-500 transition-colors">{{ $rel->name }}</h4>
                                    <p class="text-xs font-extrabold text-slate-900 dark:text-white mt-1">${{ number_format($rel->price, 2) }}</p>
                                </div>
                            </a>
                        @empty
                            <p class="text-xs text-slate-400 font-semibold">No related items found.</p>
                        @endforelse
                    </div>
                </div>

                <!-- Recently Viewed -->
                @if($recentProducts->count() > 0)
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3">Recently Viewed</h3>
                        <div class="grid grid-cols-1 gap-6">
                            @foreach($recentProducts as $rec)
                                <a href="{{ route('shop.show', $rec->id) }}" class="flex bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700/60 p-3 shadow-sm hover:shadow-md transition-shadow group items-center space-x-3">
                                    <div class="w-16 h-16 rounded-xl bg-slate-50 dark:bg-slate-900 overflow-hidden shrink-0">
                                        <img src="{{ $rec->getFirstImage() }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-grow text-left">
                                        <h4 class="text-xs font-bold text-slate-800 dark:text-white truncate group-hover:text-emerald-500 transition-colors">{{ $rec->name }}</h4>
                                        <p class="text-xs font-extrabold text-slate-900 dark:text-white mt-1">${{ number_format($rec->price, 2) }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </aside>

        </div>
    </div>
</div>
@endsection
