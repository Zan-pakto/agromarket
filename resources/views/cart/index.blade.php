@extends('layouts.app')

@section('title', 'Your Shopping Cart Basket - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Title Header -->
        <div class="mb-10 text-left">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Shopping Cart Basket</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Review your seeds and tools selections before submitting payment.</p>
        </div>

        @if(count($cart['items']) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
                
                <!-- Left Section: Cart Lines list -->
                <div class="lg:col-span-8 space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b dark:border-slate-700/60 text-slate-400 font-bold text-[10px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                    <th class="px-6 py-4">Product Details</th>
                                    <th class="px-6 py-4 text-center">Quantity</th>
                                    <th class="px-6 py-4 text-right">Price</th>
                                    <th class="px-6 py-4 text-right">Total</th>
                                    <th class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/60 text-xs sm:text-sm">
                                @foreach($cart['items'] as $item)
                                    <tr>
                                        <!-- Details -->
                                        <td class="px-6 py-5 flex items-center space-x-4">
                                            <div class="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-900 overflow-hidden shrink-0 border dark:border-slate-700">
                                                <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                            </div>
                                            <div class="text-left">
                                                <h4 class="font-bold text-slate-800 dark:text-white leading-snug">{{ $item['name'] }}</h4>
                                                <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Unit: ${{ number_format($item['price'], 2) }}</span>
                                            </div>
                                        </td>

                                        <!-- Quantity update -->
                                        <td class="px-6 py-5">
                                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center justify-center">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                <div class="flex items-center bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-2 py-1 shrink-0">
                                                    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}"
                                                           class="w-10 text-center bg-transparent border-none text-slate-800 dark:text-white text-xs font-bold focus:outline-none p-0.5">
                                                    <button type="submit" class="text-emerald-500 hover:text-emerald-600 dark:hover:text-emerald-400 p-0.5" title="Apply quantity update">
                                                        <i class="fa-solid fa-rotate-right text-xs"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="px-6 py-5 text-right font-semibold text-slate-800 dark:text-slate-300">
                                            ${{ number_format($item['price'], 2) }}
                                        </td>

                                        <!-- Line Total -->
                                        <td class="px-6 py-5 text-right font-extrabold text-slate-900 dark:text-white">
                                            ${{ number_format($item['total'], 2) }}
                                        </td>

                                        <!-- Remove button -->
                                        <td class="px-6 py-5 text-center">
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item['product_id'] }}">
                                                <button type="submit" class="text-red-500 hover:text-red-600 dark:hover:text-red-400 p-2 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition-all" title="Remove item">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Continue Shopping & Clear actions -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-2">
                        <a href="{{ route('shop.index') }}" class="flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-500 transition-colors">
                            <i class="fa-solid fa-arrow-left"></i>
                            <span>Continue Shopping Seeds & Tools</span>
                        </a>
                    </div>
                </div>

                <!-- Right Section: Order summary + Coupons -->
                <div class="lg:col-span-4 space-y-6">
                    
                    <!-- Promo Coupons card -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-xl p-6 space-y-4">
                        <h3 class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Promotional Coupon</h3>
                        
                        @if(session('coupon_code'))
                            <div class="p-3.5 bg-emerald-50 dark:bg-emerald-950/30 rounded-2xl border border-emerald-200 dark:border-emerald-900/50 flex justify-between items-center text-xs">
                                <span class="font-bold text-emerald-700 dark:text-emerald-400">
                                    <i class="fa-solid fa-tags mr-1.5"></i> Code: {{ session('coupon_code') }} Applied
                                </span>
                                <a href="{{ route('cart.coupon.remove') }}" class="text-[10px] text-red-500 font-bold underline hover:text-red-600">Remove</a>
                            </div>
                        @else
                            <form action="{{ route('cart.coupon.apply') }}" method="POST" class="flex items-stretch gap-2">
                                @csrf
                                <input type="text" name="code" placeholder="Enter coupon (e.g. AGRO20)" required 
                                       class="flex-grow text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                                <button type="submit" 
                                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl font-bold text-xs uppercase transition-all shadow-sm shrink-0">
                                    Apply
                                </button>
                            </form>
                        @endif
                    </div>

                    <!-- Cart calculation totals card -->
                    <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Order Summary</h3>

                        <div class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                            <!-- Subtotal -->
                            <div class="flex justify-between items-center">
                                <span>Cart Subtotal</span>
                                <span class="text-slate-900 dark:text-white font-bold">${{ number_format($cart['subtotal'], 2) }}</span>
                            </div>
                            
                            <!-- Discount -->
                            @if($cart['discount'] > 0)
                                <div class="flex justify-between items-center text-emerald-500">
                                    <span>Promo Discount</span>
                                    <span class="font-bold">-${{ number_format($cart['discount'], 2) }}</span>
                                </div>
                            @endif

                            <!-- Tax -->
                            <div class="flex justify-between items-center">
                                <span>Agri Tax (5%)</span>
                                <span class="text-slate-900 dark:text-white font-bold">${{ number_format($cart['tax'], 2) }}</span>
                            </div>

                            <hr class="dark:border-slate-700">

                            <!-- Grand total -->
                            <div class="flex justify-between items-center text-sm font-black text-slate-900 dark:text-white">
                                <span>Order Total</span>
                                <span class="text-base text-emerald-600 dark:text-emerald-400 font-extrabold">${{ number_format($cart['total'], 2) }}</span>
                            </div>
                        </div>

                        <!-- Checkout Button -->
                        @auth
                            <a href="{{ route('orders.checkout') }}" 
                               class="w-full py-4 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 active:scale-98 transition-all flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>Proceed to Checkout</span>
                            </a>
                        @else
                            <div class="space-y-3">
                                <a href="{{ route('login') }}?redirect=checkout" 
                                   class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-md transition-all flex items-center justify-center">
                                    Login to Checkout
                                </a>
                                <p class="text-[10px] text-slate-400 font-bold text-center">Please register or log in as a farmer to proceed.</p>
                            </div>
                        @endauth
                    </div>

                </div>
            </div>
        @else
            <!-- Empty state -->
            <div class="flex flex-col items-center justify-center py-24 bg-white dark:bg-slate-800 rounded-[40px] border border-slate-100 dark:border-slate-700/60 shadow-xl space-y-6 text-center max-w-xl mx-auto">
                <div class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500 p-8 rounded-full shadow-md">
                    <i class="fa-solid fa-basket-shopping text-4xl"></i>
                </div>
                <div class="space-y-2">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white">Your Cart is Currently Empty</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 max-w-sm">Browse our marketplace to discover premium hybrid vegetable seeds, crop fertilizers, and automatic farming tools.</p>
                </div>
                <a href="{{ route('shop.index') }}" 
                   class="px-8 py-3.5 bg-gradient-to-r from-emerald-600 to-green-500 text-white font-black text-xs uppercase rounded-xl shadow-lg transition-transform hover:scale-105 active:scale-95">
                    Start Shopping Store
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
