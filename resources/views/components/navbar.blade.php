@inject('cartService', 'App\Services\CartService')
@php
    $cartCount = count($cartService->getCart(Auth::id()));
    $unreadNotifications = Auth::check() 
        ? App\Models\Notification::where('user_id', Auth::id())->where('is_read', false)->count() 
        : 0;
@endphp

<header x-data="{ openMobile: false, openProfile: false, isDark: false }" 
        x-init="isDark = document.documentElement.classList.contains('dark')"
        class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 glassmorphism border-b shadow-sm sticky-header">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-20">
            <!-- Brand Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <div class="bg-emerald-500 text-white p-2.5 rounded-xl shadow-md group-hover:scale-110 transition-all duration-300">
                    <i class="fa-solid fa-leaf text-lg"></i>
                </div>
                <span class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-emerald-600 via-green-500 to-amber-700 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">
                    AgroMarket
                </span>
            </a>

            <!-- Desktop Primary Menu -->
            <nav class="hidden md:flex items-center space-x-8">
                <a href="{{ url('/') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('/') ? 'text-emerald-500 font-bold' : 'text-slate-600 dark:text-slate-300' }}">Home</a>
                <a href="{{ route('shop.index') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('shop*') ? 'text-emerald-500 font-bold' : 'text-slate-600 dark:text-slate-300' }}">Shop</a>
                <a href="{{ route('about') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('about') ? 'text-emerald-500 font-bold' : 'text-slate-600 dark:text-slate-300' }}">About</a>
                <a href="{{ route('contact') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('contact') ? 'text-emerald-500 font-bold' : 'text-slate-600 dark:text-slate-300' }}">Contact</a>
                <a href="{{ route('faq') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('faq') ? 'text-emerald-500 font-bold' : 'text-slate-600 dark:text-slate-300' }}">FAQ</a>
            </nav>

            <!-- User Utilities -->
            <div class="hidden md:flex items-center space-x-6">
                <!-- Theme Toggle -->
                <button @click="
                            isDark = !isDark; 
                            if (isDark) { 
                                document.documentElement.classList.add('dark'); 
                                localStorage.setItem('theme', 'dark'); 
                            } else { 
                                document.documentElement.classList.remove('dark'); 
                                localStorage.setItem('theme', 'light'); 
                            }
                        " 
                        class="p-2 rounded-xl text-slate-500 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-slate-800 transition-all duration-300"
                        title="Toggle dark mode">
                    <i x-show="!isDark" class="fa-solid fa-moon text-lg"></i>
                    <i x-show="isDark" class="fa-solid fa-sun text-lg" style="display: none;"></i>
                </button>

                <!-- Shopping Cart Bubble -->
                <a href="{{ route('cart.index') }}" class="relative p-2.5 rounded-xl text-slate-500 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-slate-800 transition-all duration-300 group">
                    <i class="fa-solid fa-basket-shopping text-lg"></i>
                    @if($cartCount > 0)
                        <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500 text-[10px] font-bold text-white shadow-md animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    <!-- Notifications Bubble -->
                    <a href="{{ route('dashboard') }}#notifications" class="relative p-2.5 rounded-xl text-slate-500 hover:text-emerald-500 hover:bg-emerald-50 dark:hover:bg-slate-800 transition-all duration-300">
                        <i class="fa-solid fa-bell text-lg"></i>
                        @if($unreadNotifications > 0)
                            <span class="absolute -top-1.5 -right-1.5 flex h-5 w-5 items-center justify-center rounded-full bg-amber-500 text-[10px] font-bold text-white shadow-md animate-bounce">
                                {{ $unreadNotifications }}
                            </span>
                        @endif
                    </a>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button @click="openProfile = !openProfile" 
                                @click.away="openProfile = false"
                                class="flex items-center space-x-2 focus:outline-none p-1.5 rounded-xl hover:bg-emerald-50 dark:hover:bg-slate-800 transition-all">
                            <img class="h-9 w-9 rounded-xl object-cover ring-2 ring-emerald-500/20" 
                                 src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true" 
                                 alt="{{ Auth::user()->name }}">
                            <div class="text-left hidden lg:block">
                                <p class="text-xs font-semibold text-slate-700 dark:text-slate-200 leading-3">{{ Auth::user()->name }}</p>
                                <span class="text-[9px] font-bold text-emerald-500 uppercase tracking-wider">{{ Auth::user()->role }}</span>
                            </div>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform" :class="{'rotate-180': openProfile}"></i>
                        </button>

                        <div x-show="openProfile" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-3 w-52 rounded-2xl bg-white dark:bg-slate-800 shadow-2xl border dark:border-slate-700 py-2 z-50 overflow-hidden"
                             style="display: none;">
                            <div class="px-4 py-2 border-b dark:border-slate-700">
                                <p class="text-xs text-slate-400">Signed in as</p>
                                <p class="text-sm font-semibold truncate text-slate-900 dark:text-white">{{ Auth::user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-emerald-50 dark:hover:bg-slate-700/50 transition-colors">
                                <i class="fa-solid fa-columns text-slate-400 w-5"></i>
                                <span>Dashboard</span>
                            </a>
                            <hr class="dark:border-slate-700 my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left flex items-center space-x-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                                    <i class="fa-solid fa-sign-out-alt w-5"></i>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 dark:text-slate-300 hover:text-emerald-500 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-green-500 text-white rounded-xl text-sm font-bold shadow-lg shadow-emerald-500/20 hover:scale-105 active:scale-95 transition-all">Register</a>
                    </div>
                @endauth
            </div>

            <!-- Mobile Menu Toggle -->
            <div class="flex items-center space-x-3 md:hidden">
                <!-- Theme Toggle Mobile -->
                <button @click="
                            isDark = !isDark; 
                            if (isDark) { 
                                document.documentElement.classList.add('dark'); 
                                localStorage.setItem('theme', 'dark');
                            } else { 
                                document.documentElement.classList.remove('dark'); 
                                localStorage.setItem('theme', 'light');
                            }
                        " 
                        class="p-2 rounded-xl text-slate-500 hover:text-emerald-500">
                    <i x-show="!isDark" class="fa-solid fa-moon"></i>
                    <i x-show="isDark" class="fa-solid fa-sun" style="display: none;"></i>
                </button>

                <!-- Cart Mobile -->
                <a href="{{ route('cart.index') }}" class="relative p-2 text-slate-500 hover:text-emerald-500">
                    <i class="fa-solid fa-basket-shopping"></i>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-emerald-500 text-[8px] font-bold text-white shadow">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <!-- Hamburger Button -->
                <button @click="openMobile = !openMobile" class="p-2 rounded-xl text-slate-600 dark:text-slate-300 focus:outline-none">
                    <i class="fa-solid fa-bars text-xl" x-show="!openMobile"></i>
                    <i class="fa-solid fa-xmark text-xl" x-show="openMobile" style="display: none;"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="openMobile" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden glassmorphism border-b py-4 px-4"
         style="display: none;">
        <div class="flex flex-col space-y-4">
            <a href="{{ url('/') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('/') ? 'text-emerald-500 font-bold' : '' }}">Home</a>
            <a href="{{ route('shop.index') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('shop*') ? 'text-emerald-500 font-bold' : '' }}">Shop</a>
            <a href="{{ route('about') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('about') ? 'text-emerald-500 font-bold' : '' }}">About</a>
            <a href="{{ route('contact') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('contact') ? 'text-emerald-500 font-bold' : '' }}">Contact</a>
            <a href="{{ route('faq') }}" class="text-sm font-semibold hover:text-emerald-500 transition-colors {{ Request::is('faq') ? 'text-emerald-500 font-bold' : '' }}">FAQ</a>

            <hr class="dark:border-slate-700">

            @auth
                <div class="flex items-center space-x-3 py-2">
                    <img class="h-10 w-10 rounded-xl object-cover ring-2 ring-emerald-500" 
                         src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=10b981&color=fff&bold=true" 
                         alt="{{ Auth::user()->name }}">
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-white">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 uppercase font-bold tracking-wider">{{ Auth::user()->role }}</p>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:text-emerald-500">
                    <i class="fa-solid fa-columns w-5"></i>
                    <span>Dashboard</span>
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left flex items-center space-x-2 text-sm font-semibold text-red-600 hover:text-red-500">
                        <i class="fa-solid fa-sign-out-alt w-5"></i>
                        <span>Log Out</span>
                    </button>
                </form>
            @else
                <div class="flex flex-col space-y-2 pt-2">
                    <a href="{{ route('login') }}" class="w-full py-2.5 text-center text-sm font-bold border rounded-xl hover:bg-emerald-50 dark:hover:bg-slate-800 text-slate-800 dark:text-white transition-colors">Login</a>
                    <a href="{{ route('register') }}" class="w-full py-2.5 text-center text-sm font-bold bg-gradient-to-r from-emerald-600 to-green-500 text-white rounded-xl shadow-md transition-all">Register</a>
                </div>
            @endauth
        </div>
    </div>
</header>
