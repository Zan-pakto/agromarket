@props(['product'])

@php
    $isInWishlist = false;
    if (Auth::check()) {
        $isInWishlist = App\Models\Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->exists();
    }
@endphp

<div class="group relative flex flex-col w-full bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 overflow-hidden" 
     x-data="{ wish: {{ $isInWishlist ? 'true' : 'false' }}, loadingCart: false }">
    
    <!-- Top actions & badges -->
    <div class="absolute top-4 left-4 right-4 z-10 flex justify-between items-center">
        <!-- Stock status badge -->
        @if($product->quantity <= 0)
            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-lg bg-red-100 text-red-800 dark:bg-red-950/40 dark:text-red-400 border border-red-200/50">
                Out of stock
            </span>
        @elseif($product->quantity < 10)
            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950/40 dark:text-amber-400 border border-amber-200/50">
                Only {{ $product->quantity }} left
            </span>
        @else
            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950/40 dark:text-emerald-400 border border-emerald-200/50">
                In Stock
            </span>
        @endif

        <!-- Wishlist toggle -->
        <button @click="
                    fetch('{{ route('wishlist.toggle', $product->id) }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (res.status === 401) {
                            window.location.href = '{{ route('login') }}';
                            return;
                        }
                        return res.json();
                    })
                    .then(data => {
                        if(data) {
                            wish = data.status === 'added';
                        }
                    });
                "
                class="p-2 rounded-full shadow-lg bg-white/80 dark:bg-slate-900/80 backdrop-blur-md text-slate-400 hover:text-red-500 hover:scale-110 active:scale-95 transition-all duration-300"
                title="Save to wishlist">
            <i class="fa-solid fa-heart text-sm transition-colors" :class="{'text-red-500': wish, 'text-slate-400 dark:text-slate-500': !wish}"></i>
        </button>
    </div>

    <!-- Product Image -->
    <a href="{{ route('shop.show', $product->id) }}" class="relative block w-full pt-[80%] bg-slate-50 dark:bg-slate-900 overflow-hidden">
        <img class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" 
             src="{{ $product->getFirstImage() }}" 
             alt="{{ $product->name }}"
             loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
    </a>

    <!-- Card Body -->
    <div class="flex flex-col flex-grow p-5 space-y-3">
        <!-- Category tag -->
        <span class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest leading-3">
            {{ $product->category->name ?? 'Agriculture' }}
        </span>

        <!-- Product title -->
        <h3 class="text-sm font-bold text-slate-800 dark:text-white leading-tight min-h-[40px] line-clamp-2 group-hover:text-emerald-500 transition-colors">
            <a href="{{ route('shop.show', $product->id) }}">{{ $product->name }}</a>
        </h3>

        <!-- Star ratings -->
        <div class="flex items-center space-x-1.5 text-xs text-amber-500">
            <div class="flex items-center">
                @for ($i = 1; $i <= 5; $i++)
                    @if ($i <= floor($product->ratings_avg))
                        <i class="fa-solid fa-star"></i>
                    @elseif ($i - 0.5 <= $product->ratings_avg)
                        <i class="fa-solid fa-star-half-stroke"></i>
                    @else
                        <i class="fa-regular fa-star text-slate-300 dark:text-slate-600"></i>
                    @endif
                @endfor
            </div>
            <span class="text-[10px] font-bold text-slate-400">({{ $product->ratings_count }})</span>
        </div>

        <!-- Seller Store Info -->
        <div class="flex items-center space-x-1.5 text-[10px] text-slate-500 dark:text-slate-400 font-semibold border-t dark:border-slate-700/60 pt-3">
            <i class="fa-solid fa-store text-emerald-500"></i>
            <span class="truncate">{{ $product->seller->name ?? 'Verified Seller' }}</span>
        </div>

        <!-- Pricing and Checkout CTA -->
        <div class="flex justify-between items-center border-t dark:border-slate-700/60 pt-3 mt-auto">
            <div class="flex flex-col">
                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Price</span>
                <span class="text-base font-extrabold text-slate-900 dark:text-white">${{ number_format($product->price, 2) }}</span>
            </div>

            <!-- Cart action -->
            @if($product->quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" @submit="loadingCart = true">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" 
                            class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 active:scale-95 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/10 flex items-center space-x-1.5 transition-all">
                        <i class="fa-solid fa-basket-shopping" x-show="!loadingCart"></i>
                        <i class="fa-solid fa-circle-notch animate-spin" x-show="loadingCart" style="display: none;"></i>
                        <span>Add</span>
                    </button>
                </form>
            @else
                <button class="px-4 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-400 dark:text-slate-500 rounded-xl text-xs font-bold cursor-not-allowed flex items-center space-x-1.5" disabled>
                    <i class="fa-solid fa-ban"></i>
                    <span>Sold Out</span>
                </button>
            @endif
        </div>
    </div>
</div>
