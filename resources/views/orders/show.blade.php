@extends('layouts.app')

@section('title', 'Order Invoice ' . $order->tracking_number . ' - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen printable-area">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
        
        <!-- Top action buttons (non-printable) -->
        <div class="flex justify-between items-center no-print">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-500 transition-colors">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Back to Dashboard</span>
            </a>
            
            <div class="flex space-x-3">
                <!-- Track button -->
                <a href="{{ route('orders.track', $order->id) }}" 
                   class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                    <span>Track Order Status</span>
                </a>
                
                <!-- Print button -->
                <button onclick="window.print()" 
                        class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Print Invoice</span>
                </button>
            </div>
        </div>

        <!-- Main Invoice Container -->
        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 sm:p-12 space-y-8">
            
            <!-- Invoice Header: Logo & Branding -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 pb-6 border-b dark:border-slate-700/60">
                <div class="flex items-center space-x-2">
                    <div class="bg-emerald-500 text-white p-2.5 rounded-xl">
                        <i class="fa-solid fa-leaf text-md"></i>
                    </div>
                    <span class="text-xl font-extrabold text-slate-900 dark:text-white tracking-tight">AgroMarket</span>
                </div>
                <div class="text-left sm:text-right">
                    <h2 class="text-lg font-black text-slate-800 dark:text-white uppercase tracking-wider">Tax Invoice</h2>
                    <p class="text-xs text-slate-400 font-bold mt-1">Invoice ID: {{ $order->tracking_number }}</p>
                    <p class="text-xs text-slate-400 font-semibold mt-0.5">Date: {{ $order->created_at ? $order->created_at->format('M d, Y H:i') : now()->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Metadata parameters: statuses -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 p-6 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border dark:border-slate-700/60 text-xs text-left">
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Order Status</p>
                    <span class="inline-block mt-2 px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-lg border {{ $order->getStatusColorClass() }}">
                        {{ $order->order_status }}
                    </span>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Payment Method</p>
                    <p class="text-slate-800 dark:text-white font-bold mt-2 uppercase tracking-wide">{{ $order->payment_method }}</p>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Payment Status</p>
                    <span class="inline-block mt-2 px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-lg border {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-950/40 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-950/40 dark:text-yellow-400' }}">
                        {{ $order->payment_status }}
                    </span>
                </div>
                <div>
                    <p class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Tracking Number</p>
                    <p class="text-slate-800 dark:text-white font-bold mt-2 truncate">{{ $order->tracking_number }}</p>
                </div>
            </div>

            <!-- Billing & Shipping Parties Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 text-xs text-left">
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Billed To (Farmer):</h3>
                    <div class="space-y-1.5 font-semibold text-slate-600 dark:text-slate-400">
                        <p class="text-slate-800 dark:text-white font-bold text-sm">{{ $order->billing_name }}</p>
                        <p><i class="fa-solid fa-envelope mr-1.5 text-slate-400"></i> {{ $order->billing_email }}</p>
                        <p><i class="fa-solid fa-phone mr-1.5 text-slate-400"></i> {{ $order->billing_phone }}</p>
                    </div>
                </div>
                <div class="space-y-3">
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Delivery Address:</h3>
                    <div class="space-y-1.5 font-semibold text-slate-600 dark:text-slate-400">
                        <p class="leading-relaxed"><i class="fa-solid fa-location-dot mr-1.5 text-slate-400"></i> {{ $order->billing_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Invoice Items Table -->
            <div class="border dark:border-slate-700 rounded-3xl overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                            <th class="px-6 py-3.5">Agriculture Item</th>
                            <th class="px-6 py-3.5 text-center">Quantity</th>
                            <th class="px-6 py-3.5 text-right">Unit Price</th>
                            <th class="px-6 py-3.5 text-right font-bold">Line Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                        @foreach($order->items as $item)
                            <tr>
                                <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                    {{ $item['name'] }}
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-600 dark:text-slate-300">
                                    {{ $item['quantity'] }}
                                </td>
                                <td class="px-6 py-4 text-right font-semibold text-slate-600 dark:text-slate-300">
                                    ${{ number_format($item['price'], 2) }}
                                </td>
                                <td class="px-6 py-4 text-right font-extrabold text-slate-900 dark:text-white">
                                    ${{ number_format($item['price'] * $item['quantity'], 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pricing Summary Box -->
            <div class="flex justify-end">
                <div class="w-full sm:w-72 space-y-3.5 text-xs text-slate-600 dark:text-slate-400 font-semibold border-t dark:border-slate-700/60 pt-4 text-left">
                    <div class="flex justify-between items-center">
                        <span>Items Subtotal</span>
                        <span class="text-slate-900 dark:text-white font-bold">${{ number_format($order->total_amount - ($order->total_amount * 0.05 / 1.05) + $order->discount_amount, 2) }}</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="flex justify-between items-center text-emerald-500">
                            <span>Coupon Discount ({{ $order->coupon_code }})</span>
                            <span class="font-bold">-${{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span>Agricultural Tax (5%)</span>
                        <span class="text-slate-900 dark:text-white font-bold">${{ number_format($order->total_amount * 0.05 / 1.05, 2) }}</span>
                    </div>
                    <hr class="dark:border-slate-700">
                    <div class="flex justify-between items-center text-sm font-black text-slate-900 dark:text-white">
                        <span>Invoice Total</span>
                        <span class="text-base text-emerald-600 dark:text-emerald-400 font-extrabold">${{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Invoice Footer -->
            <div class="text-center text-[10px] text-slate-400 font-bold border-t dark:border-slate-700/60 pt-6">
                <p>Thank you for trading with AgroMarket. Empowering Farmers, Growing Futures.</p>
                <p class="mt-1">For any dispatch issues or supplier returns, reach us at support@agromarket.com</p>
            </div>

        </div>
    </div>
</div>

<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background-color: white !important;
            color: black !important;
        }
        .printable-area {
            padding: 0 !important;
            background-color: transparent !important;
        }
    }
</style>
@endsection
