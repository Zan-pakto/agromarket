@extends('layouts.app')

@section('title', 'Finalize Checkout - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-10 text-left">
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Secure Checkout</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Submit billing profiles and choose a payment system to complete order.</p>
        </div>

        <form action="{{ route('orders.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start" x-data="{ method: 'cod' }">
            @csrf
            
            <!-- Left Side: Billing Address & Payment methods -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Billing Address card -->
                <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">1. Billing & Shipping Address</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required 
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white placeholder-slate-500 font-semibold">
                        </div>

                        <!-- Email Address -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required 
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white placeholder-slate-500 font-semibold">
                        </div>

                        <!-- Telephone Number -->
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Phone Number</label>
                            <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" required 
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white placeholder-slate-500 font-semibold" placeholder="+1 (555) 000-0000">
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Delivery Address Details</label>
                        <textarea name="address" rows="3" required placeholder="Street address, city, state, zip code, farm details..." 
                                  class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white placeholder-slate-500 font-semibold">{{ old('address', Auth::user()->address) }}</textarea>
                    </div>
                </div>

                <!-- Payment Methods Choice Card -->
                <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6">
                    <h3 class="text-base font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">2. Choose Payment Method</h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <!-- COD option -->
                        <label class="flex flex-col p-5 border rounded-2xl cursor-pointer hover:border-emerald-500 transition-all"
                               :class="method === 'cod' ? 'border-emerald-500 bg-emerald-50/20 dark:bg-emerald-950/20 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700'">
                            <input type="radio" name="payment_method" value="cod" class="hidden" @click="method = 'cod'" checked>
                            <i class="fa-solid fa-hand-holding-dollar text-xl text-emerald-500 mb-2"></i>
                            <span class="text-xs font-bold text-slate-800 dark:text-white">Cash on Delivery</span>
                            <span class="text-[9px] text-slate-400 font-semibold mt-1">Pay when seeds reach your farm.</span>
                        </label>

                        <!-- Stripe option -->
                        <label class="flex flex-col p-5 border rounded-2xl cursor-pointer hover:border-emerald-500 transition-all"
                               :class="method === 'stripe' ? 'border-emerald-500 bg-emerald-50/20 dark:bg-emerald-950/20 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700'">
                            <input type="radio" name="payment_method" value="stripe" class="hidden" @click="method = 'stripe'">
                            <i class="fa-brands fa-stripe text-xl text-indigo-500 mb-2"></i>
                            <span class="text-xs font-bold text-slate-800 dark:text-white">Stripe Card</span>
                            <span class="text-[9px] text-slate-400 font-semibold mt-1">Pay instantly with Credit/Debit.</span>
                        </label>

                        <!-- Razorpay option -->
                        <label class="flex flex-col p-5 border rounded-2xl cursor-pointer hover:border-emerald-500 transition-all"
                               :class="method === 'razorpay' ? 'border-emerald-500 bg-emerald-50/20 dark:bg-emerald-950/20 ring-2 ring-emerald-500/20' : 'border-slate-200 dark:border-slate-700'">
                            <input type="radio" name="payment_method" value="razorpay" class="hidden" @click="method = 'razorpay'">
                            <i class="fa-solid fa-credit-card text-xl text-blue-500 mb-2"></i>
                            <span class="text-xs font-bold text-slate-800 dark:text-white">Razorpay Wallet</span>
                            <span class="text-[9px] text-slate-400 font-semibold mt-1">Netbanking, UPI & Wallets.</span>
                        </label>
                    </div>

                    <!-- Stripe Sandbox Frame (visual) -->
                    <div x-show="method === 'stripe'" x-transition class="p-6 rounded-2xl bg-indigo-50/30 dark:bg-slate-900/50 border dark:border-slate-700 space-y-4 text-left" style="display: none;">
                        <h4 class="text-xs font-bold text-indigo-500 uppercase tracking-widest"><i class="fa-brands fa-stripe-s mr-1"></i> Stripe Secure Checkout</h4>
                        <div class="grid grid-cols-3 gap-3">
                            <div class="col-span-3 space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Cardholder Name</label>
                                <input type="text" placeholder="John Doe" disabled class="w-full text-xs bg-white dark:bg-slate-950 border dark:border-slate-700 rounded-lg px-3 py-2 text-slate-400 cursor-not-allowed">
                            </div>
                            <div class="col-span-3 space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Card Number</label>
                                <div class="relative flex items-center">
                                    <input type="text" placeholder="•••• •••• •••• ••••" disabled class="w-full text-xs bg-white dark:bg-slate-950 border dark:border-slate-700 rounded-lg pl-9 pr-3 py-2 text-slate-400 cursor-not-allowed">
                                    <i class="fa-regular fa-credit-card absolute left-3 text-slate-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">Expiry</label>
                                <input type="text" placeholder="MM/YY" disabled class="w-full text-xs bg-white dark:bg-slate-950 border dark:border-slate-700 rounded-lg px-3 py-2 text-slate-400 cursor-not-allowed">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[8px] font-black text-slate-400 uppercase tracking-widest">CVC</label>
                                <input type="text" placeholder="•••" disabled class="w-full text-xs bg-white dark:bg-slate-950 border dark:border-slate-700 rounded-lg px-3 py-2 text-slate-400 cursor-not-allowed">
                            </div>
                        </div>
                        <p class="text-[9px] text-slate-400 font-semibold"><i class="fa-solid fa-info-circle mr-1"></i> Card entries are disabled. Selecting Stripe automatically processes secure mocked sandbox authorization upon clicking checkout.</p>
                    </div>

                    <!-- Razorpay Sandbox details -->
                    <div x-show="method === 'razorpay'" x-transition class="p-6 rounded-2xl bg-blue-50/30 dark:bg-slate-900/50 border dark:border-slate-700 text-left space-y-2" style="display: none;">
                        <h4 class="text-xs font-bold text-blue-500 uppercase tracking-widest"><i class="fa-solid fa-shield-halved mr-1"></i> Razorpay Payment Gateway</h4>
                        <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-semibold">
                            UPI ID, credit cards, or digital wallets will pop up securely. Selecting Razorpay processes a mock invoice completion instantly upon submission.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order Summary & Placement -->
            <div class="lg:col-span-4 space-y-6">
                <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 space-y-6">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Order Summary</h3>

                    <!-- Mini Cart items list -->
                    <div class="space-y-4 max-h-48 overflow-y-auto pr-2 divide-y divide-slate-50 dark:divide-slate-700/60">
                        @foreach($cart['items'] as $item)
                            <div class="flex items-center justify-between text-xs pt-3 first:pt-0">
                                <div class="flex items-center space-x-2.5 truncate">
                                    <div class="w-10 h-10 rounded-xl bg-slate-50 dark:bg-slate-900 overflow-hidden shrink-0">
                                        <img src="{{ $item['image'] }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="truncate text-left">
                                        <h4 class="font-bold text-slate-800 dark:text-white truncate">{{ $item['name'] }}</h4>
                                        <span class="text-[9px] text-slate-400 font-bold uppercase tracking-wider">Qty: {{ $item['quantity'] }}</span>
                                    </div>
                                </div>
                                <span class="font-bold text-slate-900 dark:text-white shrink-0 ml-3">${{ number_format($item['total'], 2) }}</span>
                            </div>
                        @endforeach
                    </div>

                    <hr class="dark:border-slate-700">

                    <!-- Totals breakdown -->
                    <div class="space-y-3.5 text-xs text-slate-600 dark:text-slate-400 font-semibold">
                        <div class="flex justify-between items-center">
                            <span>Cart Subtotal</span>
                            <span class="text-slate-900 dark:text-white font-bold">${{ number_format($cart['subtotal'], 2) }}</span>
                        </div>
                        @if($cart['discount'] > 0)
                            <div class="flex justify-between items-center text-emerald-500">
                                <span>Discount Approved</span>
                                <span class="font-bold">-${{ number_format($cart['discount'], 2) }}</span>
                            </div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span>Agricultural Tax (5%)</span>
                            <span class="text-slate-900 dark:text-white font-bold">${{ number_format($cart['tax'], 2) }}</span>
                        </div>
                        <hr class="dark:border-slate-700">
                        <div class="flex justify-between items-center text-sm font-black text-slate-900 dark:text-white">
                            <span>Grand Total</span>
                            <span class="text-base text-emerald-600 dark:text-emerald-400 font-extrabold">${{ number_format($cart['total'], 2) }}</span>
                        </div>
                    </div>

                    <!-- Checkout Submit Button -->
                    <button type="submit" 
                            class="w-full py-4 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white rounded-2xl font-black text-xs uppercase tracking-wider shadow-lg shadow-emerald-500/20 active:scale-98 transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-lock text-sm"></i>
                        <span>Confirm and Place Order</span>
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection
