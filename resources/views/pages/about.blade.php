@extends('layouts.app')

@section('title', 'About AgroMarket - Fostering Agriculture Connections')

@section('content')
<div class="py-16 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-400 text-left">
            <a href="{{ url('/') }}" class="hover:text-emerald-500 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-slate-600 dark:text-slate-200">About Us</span>
        </nav>

        <!-- Header -->
        <div class="text-left space-y-4">
            <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Our Vision & Mission</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white leading-tight">Empowering Farmers, Growing Sustainable Futures</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                AgroMarket is a premium web platform built to connect local agricultural growers directly with trusted supplier organizations, bypassing overhead costs.
            </p>
        </div>

        <!-- Organic Story block -->
        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-12 text-left space-y-6">
            <h2 class="text-lg font-black text-slate-800 dark:text-white border-l-4 border-emerald-500 pl-3">The AgroMarket Story</h2>
            
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                Farming is the backbone of global sustainability, yet sourcing high-yield seeds and machinery continues to be bogged down by middlemen, excessive prices, and poor inventory transparency. 
            </p>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-semibold">
                AgroMarket resolves this challenge. By building an elegant, MVC-driven marketplace, we allow verified agriculture suppliers to publish stock levels, detailed seed specs, NPK values of fertilizers, and power weeders directly. Farmers can browse catalogs, compare star ratings from actual buyers, add to baskets, and choose Cash on Delivery for absolute peace of mind.
            </p>
        </div>

        <!-- Core values grid -->
        <div class="space-y-6">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider text-left border-l-3 border-emerald-500 pl-3">Our Core Principles</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-left">
                <!-- Value 1 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border dark:border-slate-700/60 p-6 space-y-3">
                    <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl inline-block">
                        <i class="fa-solid fa-square-check text-lg"></i>
                    </div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Guaranteed Quality</h4>
                    <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                        Every dealer undergoes background checks. Seeds catalogs include official germination rates.
                    </p>
                </div>

                <!-- Value 2 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border dark:border-slate-700/60 p-6 space-y-3">
                    <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl inline-block">
                        <i class="fa-solid fa-users text-lg"></i>
                    </div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Direct Marketplace</h4>
                    <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                        We connect sellers directly to farmers. Transparent pricing, no commission margins.
                    </p>
                </div>

                <!-- Value 3 -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl border dark:border-slate-700/60 p-6 space-y-3">
                    <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl inline-block">
                        <i class="fa-solid fa-seedling text-lg"></i>
                    </div>
                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Eco-friendly focus</h4>
                    <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                        We advocate for organic vermicomposts, natural bio-pesticides, and chemical-free seed varieties.
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
