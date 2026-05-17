@extends('layouts.app')

@section('title', 'Frequently Asked Questions - AgroMarket')

@section('content')
<div class="py-16 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-400 text-left">
            <a href="{{ url('/') }}" class="hover:text-emerald-500 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-slate-600 dark:text-slate-200">Frequently Asked Questions</span>
        </nav>

        <!-- Header -->
        <div class="text-left space-y-4">
            <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Help Center FAQ</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white leading-tight">Got Questions? We Have Organic Answers</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Explore guidelines about shopping, payments gateways, shipping trackers, and merchant registrations.
            </p>
        </div>

        <!-- FAQs Accordions list -->
        <div class="space-y-6 text-left" x-data="{ activeFaq: null }">
            
            <!-- Question 1 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
                <button @click="activeFaq = activeFaq === 1 ? null : 1" 
                        class="w-full px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-750/30 transition-all duration-300">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white leading-snug">How do I apply promotional coupon codes?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="{'rotate-180 text-emerald-500': activeFaq === 1}"></i>
                </button>
                <div x-show="activeFaq === 1" x-transition class="px-6 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed font-semibold">
                    You can apply active promo codes (such as our limited-time <span class="bg-amber-400 text-slate-900 px-1.5 py-0.5 rounded font-black">AGRO20</span> percent code) directly inside the Shopping Cart Summary panel. Type the code in the promo input box and click apply. The discount will instantly recalculate sub-totals.
                </div>
            </div>

            <!-- Question 2 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
                <button @click="activeFaq = activeFaq === 2 ? null : 2" 
                        class="w-full px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-750/30 transition-all duration-300">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white leading-snug">What payment options does AgroMarket accept?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="{'rotate-180 text-emerald-500': activeFaq === 2}"></i>
                </button>
                <div x-show="activeFaq === 2" x-transition class="px-6 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed font-semibold">
                    We accept Cash on Delivery (COD) which allows farmers to pay once shipment arrives at the homestead/farm gate, Stripe credit and debit cards, and Razorpay integrations including digital wallets and netbanking options.
                </div>
            </div>

            <!-- Question 3 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
                <button @click="activeFaq = activeFaq === 3 ? null : 3" 
                        class="w-full px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-750/30 transition-all duration-300">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white leading-snug">How do I register as an approved supplier / seller?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="{'rotate-180 text-emerald-500': activeFaq === 3}"></i>
                </button>
                <div x-show="activeFaq === 3" x-transition class="px-6 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed font-semibold">
                    To maintain standard quality, all new seller accounts are flagged as "pending". Select the merchant checkbox during the standard registration flow and fill in your physical warehouse coordinates. A site administrator will review and approve your account within 24 hours.
                </div>
            </div>

            <!-- Question 4 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
                <button @click="activeFaq = activeFaq === 4 ? null : 4" 
                        class="w-full px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-750/30 transition-all duration-300">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white leading-snug">What is the estimated dispatch time for heavy machinery rototillers?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="{'rotate-180 text-emerald-500': activeFaq === 4}"></i>
                </button>
                <div x-show="activeFaq === 4" x-transition class="px-6 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed font-semibold">
                    Heavy equipment (such as our power weeders or drillers) are dispatched via AgroLogistics premium cargo carriers. Typical delivery time ranges from 3 to 5 business days, depending on homestead remoteness. Courier partners will call your phone line in advance of arrival.
                </div>
            </div>

            <!-- Question 5 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm overflow-hidden">
                <button @click="activeFaq = activeFaq === 5 ? null : 5" 
                        class="w-full px-6 py-5 flex justify-between items-center focus:outline-none hover:bg-slate-50 dark:hover:bg-slate-750/30 transition-all duration-300">
                    <span class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white leading-snug">Can I track my shipments dynamically?</span>
                    <i class="fa-solid fa-chevron-down text-xs text-slate-400 transition-transform" :class="{'rotate-180 text-emerald-500': activeFaq === 5}"></i>
                </button>
                <div x-show="activeFaq === 5" x-transition class="px-6 pb-6 text-xs text-slate-500 dark:text-slate-450 leading-relaxed font-semibold">
                    Yes! Log in and open your Purchase History list under the Farmer Dashboard, click details on any placed order, and hit Track Order Status. You can see a live progress bar reflecting dispatcher milestones.
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
