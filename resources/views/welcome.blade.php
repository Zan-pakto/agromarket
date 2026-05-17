@extends('layouts.app')

@section('title', 'AgroMarket - Premium Agriculture Seed and Tool Marketplace')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-emerald-950 via-emerald-900 to-amber-950 text-white py-24 lg:py-32 overflow-hidden">
    <!-- Abstract background leaves decoration -->
    <div class="absolute inset-0 opacity-10 mix-blend-overlay bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-green-300 via-emerald-600 to-slate-900"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            <!-- Hero Text -->
            <div class="lg:col-span-7 space-y-8 text-left">
                <span class="inline-flex items-center space-x-2 px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-bold uppercase tracking-wider border border-emerald-500/30">
                    <i class="fa-solid fa-leaf"></i>
                    <span>Connecting Farmers & Trusted Suppliers</span>
                </span>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
                    Sow the Seeds of <br>
                    <span class="bg-gradient-to-r from-emerald-400 via-green-400 to-amber-300 bg-clip-text text-transparent">Bumper Harvests</span>
                </h1>
                <p class="text-sm sm:text-base text-slate-300 max-w-xl leading-relaxed">
                    Source premium, high-germination organic seeds, soil-enriching bio-fertilizers, automatic micro-irrigation kits, and heavy machinery directly from approved agriculture suppliers.
                </p>

                <!-- Search Bar Container -->
                <form action="{{ route('shop.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch gap-2.5 p-2 bg-white/10 backdrop-blur-md rounded-2xl border border-white/20 shadow-xl max-w-lg">
                    <div class="flex-grow flex items-center px-3 text-slate-400 focus-within:text-white">
                        <i class="fa-solid fa-magnifying-glass text-sm mr-2.5"></i>
                        <input type="text" name="search" placeholder="Search tomato seeds, compost, power weeders..." 
                               class="w-full bg-transparent border-none text-white text-sm focus:outline-none placeholder-slate-400">
                    </div>
                    <button type="submit" 
                            class="px-6 py-3.5 bg-gradient-to-r from-emerald-500 to-green-500 hover:from-emerald-400 hover:to-green-400 text-white text-xs font-extrabold uppercase rounded-xl transition-all shadow-lg shadow-emerald-500/30">
                        Find Products
                    </button>
                </form>

                <!-- Statistics badges -->
                <div class="grid grid-cols-3 gap-4 pt-4 max-w-md text-xs sm:text-sm">
                    <div>
                        <p class="text-2xl font-black text-emerald-400">92%</p>
                        <p class="text-slate-400 text-xs font-semibold">Seed Germination Rate</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-amber-400">20K+</p>
                        <p class="text-slate-400 text-xs font-semibold">Verified Farmers Served</p>
                    </div>
                    <div>
                        <p class="text-2xl font-black text-emerald-400">100%</p>
                        <p class="text-slate-400 text-xs font-semibold">Quality Guaranteed</p>
                    </div>
                </div>
            </div>

            <!-- Hero Illustration Image -->
            <div class="lg:col-span-5 relative hidden lg:block">
                <div class="relative w-full aspect-square rounded-[40px] overflow-hidden border-4 border-emerald-500/20 shadow-2xl shadow-emerald-950/50">
                    <img src="https://images.unsplash.com/photo-1500937386664-56d1dfef3854?w=800&auto=format&fit=crop" 
                         class="w-full h-full object-cover" 
                         alt="Beautiful Green Organic Agriculture Field">
                </div>
                <!-- Mini floating badges -->
                <div class="absolute -top-6 -right-6 glassmorphism border dark:border-slate-700 p-4 rounded-2xl shadow-xl flex items-center space-x-3 animate-bounce">
                    <div class="p-2 bg-emerald-500 text-white rounded-xl">
                        <i class="fa-solid fa-certificate"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-500">SUPPLIER</p>
                        <p class="text-xs font-bold text-slate-800 dark:text-white">100% Verified</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-emerald-50/20 dark:bg-slate-900/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
        <div class="max-w-2xl mx-auto space-y-4">
            <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Browse by Category</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Choose Product Categories</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Explore specialized collections of high-yielding agricultural inputs, machinery, and tools.
            </p>
        </div>

        <!-- Categories grid -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop.index') }}?category={{ $category->id }}" 
                   class="group flex flex-col items-center p-6 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm hover:shadow-md hover:border-emerald-300 dark:hover:border-slate-600 transition-all duration-300">
                    <div class="mb-4 p-4 rounded-full bg-emerald-50 dark:bg-slate-700 text-emerald-500 dark:text-emerald-400 group-hover:bg-emerald-500 group-hover:text-white transition-all duration-300">
                        <i class="fa-solid {{ $category->icon ?? 'fa-seedling' }} text-xl"></i>
                    </div>
                    <h3 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white group-hover:text-emerald-500 transition-colors">
                        {{ $category->name }}
                    </h3>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
            <div class="space-y-4">
                <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Trending Products</span>
                <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Our Featured Products</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 max-w-md">
                    Top-rated fertilizers, seeds, and equipment verified by farmers for guaranteed crop yields.
                </p>
            </div>
            <a href="{{ route('shop.index') }}" 
               class="px-5 py-3 border border-emerald-500 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-500 hover:text-white rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5 shadow-sm">
                <span>View Full Store</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </div>
</section>

<!-- CTA Promo Banner -->
<section class="py-12 bg-emerald-50/20 dark:bg-slate-900/30">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="relative bg-gradient-to-r from-emerald-600 to-green-500 rounded-[40px] shadow-2xl p-10 lg:p-16 overflow-hidden">
            <!-- Background circles -->
            <div class="absolute -top-12 -left-12 w-64 h-64 bg-white/5 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-white/5 rounded-full blur-xl"></div>
            
            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center text-white">
                <div class="lg:col-span-8 space-y-4">
                    <span class="text-[10px] font-black uppercase tracking-widest bg-emerald-700/50 px-3 py-1 rounded-full">Limited Time Promotion</span>
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold leading-tight">Get 20% Discount On Your First Seeds Order!</h2>
                    <p class="text-xs sm:text-sm text-slate-100 max-w-xl font-medium">
                        Apply coupon code <span class="bg-amber-400 text-slate-900 px-2.5 py-0.5 rounded-lg font-black text-xs">AGRO20</span> at your shopping cart check out to deduct 20% on any purchases above $50.00.
                    </p>
                </div>
                <div class="lg:col-span-4 lg:text-right">
                    <a href="{{ route('shop.index') }}" 
                       class="inline-block px-8 py-4 bg-white text-emerald-700 hover:bg-slate-50 font-extrabold text-xs uppercase rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all">
                        Shop Seeds Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-12">
        <div class="max-w-2xl mx-auto space-y-4">
            <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Farmer Reviews</span>
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Trusted by Over 20,000+ Farmers</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Discover testimonies from agricultural growers who optimized crop health using our marketplace.
            </p>
        </div>

        <!-- Testimonial Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($testimonials as $test)
                <div class="flex flex-col p-8 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm space-y-6">
                    <div class="flex text-amber-500 text-sm">
                        @for ($i = 0; $i < $test['rating']; $i++)
                            <i class="fa-solid fa-star"></i>
                        @endfor
                    </div>
                    <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 italic leading-relaxed text-left flex-grow">
                        "{{ $test['feedback'] }}"
                    </p>
                    <div class="flex items-center space-x-3 pt-4 border-t dark:border-slate-700/60">
                        <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-emerald-500/20" 
                             src="{{ $test['avatar'] }}" 
                             alt="{{ $test['name'] }}">
                        <div class="text-left">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">{{ $test['name'] }}</h4>
                            <p class="text-[10px] text-slate-400 font-semibold">{{ $test['role'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Sellers CTA Section -->
<section class="py-20 bg-gradient-to-br from-amber-50/50 to-emerald-50/20 dark:from-slate-900/10 dark:to-slate-900/30 border-t dark:border-slate-800">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
        <div class="bg-amber-500 text-white p-4 inline-block rounded-full shadow-lg">
            <i class="fa-solid fa-store text-2xl"></i>
        </div>
        <div class="space-y-4 max-w-xl mx-auto">
            <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Are You a Certified Agricultural Supplier?</h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Join AgroMarket as an approved seller. Reach thousands of farmers, publish seed image catalogs, manage inventories, and increase sales instantly.
            </p>
        </div>
        <div class="flex justify-center space-x-4">
            <a href="{{ route('register') }}?role=seller" 
               class="px-8 py-4 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-extrabold text-xs uppercase rounded-2xl shadow-xl hover:scale-105 active:scale-95 transition-all">
                Become a Seller
            </a>
            <a href="{{ route('contact') }}" 
               class="px-8 py-4 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 font-extrabold text-xs uppercase rounded-2xl transition-all">
                Contact Sales
            </a>
        </div>
    </div>
</section>
@endsection
