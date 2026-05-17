@extends('layouts.app')

@section('title', 'Track Order Shipment - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Back Link -->
        <a href="{{ route('orders.show', $order->id) }}" class="flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-500 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Return to Order Invoice</span>
        </a>

        <!-- Main Tracking Block -->
        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-12 space-y-12">
            
            <!-- Details header -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pb-6 border-b dark:border-slate-700/60">
                <div class="space-y-1 text-left">
                    <h1 class="text-xl sm:text-2xl font-black text-slate-800 dark:text-white">Track Order Shipment</h1>
                    <p class="text-xs text-slate-400 font-semibold">Tracking Number: <strong class="text-slate-700 dark:text-white">{{ $order->tracking_number }}</strong></p>
                </div>
                
                <div class="px-4 py-2 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/50 rounded-2xl text-left sm:text-right shrink-0">
                    <p class="text-[9px] font-black text-emerald-600 dark:text-emerald-400 uppercase tracking-widest">Est. Delivery</p>
                    <p class="text-xs font-bold text-slate-800 dark:text-white mt-0.5">{{ $order->created_at ? $order->created_at->addDays(5)->format('M d, Y') : now()->addDays(5)->format('M d, Y') }}</p>
                </div>
            </div>

            @php
                $status = $order->order_status;
                $step = 1; // placed
                if (in_array($status, ['confirmed', 'shipped', 'delivered'])) {
                    $step = 2;
                }
                if (in_array($status, ['shipped', 'delivered'])) {
                    $step = 3;
                }
                if ($status === 'delivered') {
                    $step = 5; // bypass 4 (out for delivery) and jump straight to delivered for simplicity
                }
            @endphp

            <!-- Interactive visual timeline progress bar -->
            <div class="relative py-6">
                <!-- Line background -->
                <div class="absolute top-1/2 left-0 right-0 h-1 bg-slate-100 dark:bg-slate-700 -translate-y-1/2 rounded-full no-print"></div>
                <!-- Highlight line -->
                <div class="absolute top-1/2 left-0 h-1 bg-emerald-500 -translate-y-1/2 rounded-full transition-all duration-500 no-print"
                     style="width: {{ $step == 1 ? '0%' : ($step == 2 ? '33%' : ($step == 3 ? '66%' : '100%')) }};"></div>

                <!-- Steps circles grid -->
                <div class="relative z-10 flex justify-between items-center text-center">
                    
                    <!-- Placed (Step 1) -->
                    <div class="flex flex-col items-center space-y-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-lg transition-all duration-300 bg-emerald-500 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/40">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-800 dark:text-white">Placed</p>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Order Received</p>
                        </div>
                    </div>

                    <!-- Confirmed (Step 2) -->
                    <div class="flex flex-col items-center space-y-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-lg transition-all duration-300 
                                    {{ $step >= 2 ? 'bg-emerald-500 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/40' : 'bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-400' }}">
                            <i class="fa-solid fa-check"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold {{ $step >= 2 ? 'text-slate-800 dark:text-white' : 'text-slate-400' }}">Confirmed</p>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">Supplier Approved</p>
                        </div>
                    </div>

                    <!-- Shipped (Step 3) -->
                    <div class="flex flex-col items-center space-y-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-lg transition-all duration-300 
                                    {{ $step >= 3 ? 'bg-emerald-500 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/40' : 'bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-400' }}">
                            <i class="fa-solid fa-truck-fast"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold {{ $step >= 3 ? 'text-slate-800 dark:text-white' : 'text-slate-400' }}">Dispatched</p>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">En Route</p>
                        </div>
                    </div>

                    <!-- Delivered (Step 5) -->
                    <div class="flex flex-col items-center space-y-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-xs shadow-lg transition-all duration-300 
                                    {{ $step >= 5 ? 'bg-emerald-500 text-white ring-4 ring-emerald-100 dark:ring-emerald-900/40' : 'bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-700 text-slate-400' }}">
                            <i class="fa-solid fa-house-chimney-user"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold {{ $step >= 5 ? 'text-slate-800 dark:text-white' : 'text-slate-400' }}">Delivered</p>
                            <p class="text-[9px] text-slate-400 font-semibold mt-0.5">At Farm Gate</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Cancelled notification -->
            @if($status === 'cancelled')
                <div class="p-6 rounded-3xl bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 flex items-start space-x-3 text-left">
                    <i class="fa-solid fa-circle-exclamation text-red-500 text-xl shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="text-xs sm:text-sm font-bold text-red-800 dark:text-red-400">Order Shipment Cancelled</h4>
                        <p class="text-xs text-red-600 dark:text-red-500 mt-1 font-semibold">
                            This order was cancelled by the supplier or platform administrator. Refund has been initiated if paid online. Contact client support for detailed reasoning.
                        </p>
                    </div>
                </div>
            @endif

            <!-- Shipping information & instructions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-6 border-t dark:border-slate-700/60 text-xs text-left">
                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Shipping Partner Info</h3>
                    <div class="space-y-2 font-semibold text-slate-600 dark:text-slate-400">
                        <p><i class="fa-solid fa-box mr-2 text-slate-400"></i> Carrier: <strong class="text-slate-800 dark:text-white font-bold">AgroLogistics Premium</strong></p>
                        <p><i class="fa-solid fa-hashtag mr-2 text-slate-400"></i> Tracking Code: <strong class="text-slate-800 dark:text-white font-bold">{{ $order->tracking_number }}</strong></p>
                        <p><i class="fa-solid fa-phone mr-2 text-slate-400"></i> Logistics Support: <strong class="text-slate-800 dark:text-white font-bold">+1 (555) 081-3000</strong></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Courier Notes</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-semibold">
                        Couriers will call you on the phone number provided (<strong class="text-slate-700 dark:text-white font-bold">{{ $order->billing_phone }}</strong>) to verify your presence at the homestead/farm gate before dropping off heavy machinery or seeds packages. Please keep your telephone active.
                    </p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
