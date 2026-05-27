@extends('layouts.app')

@section('title', 'Contact Customer Support - AgroMarket')

@section('content')
<div class="py-16 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        
        <!-- Breadcrumbs -->
        <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-400 text-left">
            <a href="{{ url('/') }}" class="hover:text-emerald-500 transition-colors">Home</a>
            <i class="fa-solid fa-chevron-right text-[8px]"></i>
            <span class="text-slate-600 dark:text-slate-200">Contact Us</span>
        </nav>

        <!-- Header -->
        <div class="text-left space-y-4">
            <span class="text-xs font-extrabold text-emerald-500 uppercase tracking-widest">Get In Touch</span>
            <h1 class="text-3xl sm:text-4xl font-black text-slate-900 dark:text-white leading-tight">We are Here to Help Your Farm Grow</h1>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">
                Contact our customer support team for inquiries about seed quality, bulk tractor shipments, or seller application registrations.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Left Side: contact info -->
            <div class="lg:col-span-5 space-y-6 text-left">
                <!-- Address card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl shrink-0">
                            <i class="fa-solid fa-map-location-dot text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Corporate Headquarters</h4>
                            <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                                Global Agriculture Hub, Suite 101, Washington, DC
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Phone card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl shrink-0">
                            <i class="fa-solid fa-phone-volume text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Customer Hotlines</h4>
                            <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                                +1 (555) 019-2831 <br>
                                Monday - Friday, 9:00 AM - 6:00 PM EST
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Email card -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-4">
                    <div class="flex items-start space-x-4">
                        <div class="p-3 bg-emerald-50 dark:bg-slate-900 text-emerald-500 rounded-xl shrink-0">
                            <i class="fa-solid fa-envelope-open-text text-lg"></i>
                        </div>
                        <div class="space-y-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-800 dark:text-white">Online Support</h4>
                            <p class="text-[11px] text-slate-550 dark:text-slate-400 font-semibold leading-relaxed">
                                support@agromarket.com <br>
                                Inquiries are answered within 24 business hours.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Contact Form -->
            <div class="lg:col-span-7 bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 text-left space-y-6">
                <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-3 border-b dark:border-slate-700/60">Send Us a Direct Message</h3>
                
                @if(session('success'))
                    <div class="p-4 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-semibold mb-4">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> {{ session('success') }}
                    </div>
                @endif
                
                @if($errors->any())
                    <div class="p-4 bg-red-50 dark:bg-red-950/20 text-red-600 dark:text-red-400 rounded-xl text-xs font-semibold mb-4">
                        <i class="fa-solid fa-triangle-exclamation mr-1.5"></i> Please check your inputs and try again.
                    </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Full Name</label>
                            <input type="text" name="name" required placeholder="John Doe" value="{{ old('name') }}"
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Email Address</label>
                            <input type="email" name="email" required placeholder="john@doe.com" value="{{ old('email') }}"
                                   class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Subject Description</label>
                        <input type="text" name="subject" required placeholder="e.g. Order Tracking ID query" value="{{ old('subject') }}"
                               class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Message details</label>
                        <textarea name="message" rows="4" required placeholder="State your requirements, order code, or supplier issue in full..." 
                                  class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">{{ old('message') }}</textarea>
                    </div>

                    <button type="submit" 
                            class="px-8 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs uppercase rounded-xl shadow-md transition-all active:scale-95">
                        Send Message Support
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
