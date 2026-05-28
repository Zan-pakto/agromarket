@extends('layouts.app')

@section('title', 'Seller Application Status - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen flex items-center justify-center">
    <div class="max-w-xl w-full mx-auto px-4 sm:px-6">
        
        <!-- Premium glassmorphism container -->
        <div class="bg-white dark:bg-slate-800 rounded-[40px] shadow-2xl border border-slate-100 dark:border-slate-700/60 p-8 sm:p-12 relative overflow-hidden text-center">
            <!-- Ambient glowing backgrounds -->
            <div class="absolute -top-24 -left-24 w-72 h-72 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-amber-500/5 dark:bg-amber-500/10 rounded-full blur-3xl"></div>

            <div class="relative z-10 space-y-8">
                
                @if($user->status === 'pending')
                    <!-- Pending Icon with micro-animation -->
                    <div class="inline-flex items-center justify-center h-24 w-24 rounded-[30px] bg-amber-50 dark:bg-amber-950/40 text-amber-500 shadow-lg border border-amber-100 dark:border-amber-900/30 animate-pulse">
                        <i class="fa-solid fa-hourglass-half text-4xl"></i>
                    </div>

                    <div class="space-y-3">
                        <span class="inline-block px-3 py-1 rounded-full bg-amber-100 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 text-[10px] font-black uppercase tracking-wider">Application Under Review</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white">Store Approvals Pending</h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-md mx-auto">
                            Thank you for applying to sell on AgroMarket! Our administration team is reviewing your store details, phone line, and dispatch address.
                        </p>
                    </div>

                    <!-- Progress tracking -->
                    <div class="bg-slate-50 dark:bg-slate-900/40 border dark:border-slate-700/50 rounded-3xl p-6 text-left space-y-4 max-w-sm mx-auto">
                        <div class="flex items-center space-x-3.5">
                            <div class="h-6 w-6 rounded-full bg-emerald-500 text-white flex items-center justify-center text-[10px] font-black">
                                <i class="fa-solid fa-check"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-slate-800 dark:text-white">Store Details Registered</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">{{ $user->created_at ? $user->created_at->format('M d, Y') : 'Just now' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3.5">
                            <div class="h-6 w-6 rounded-full bg-amber-500 text-white flex items-center justify-center text-[10px] font-black animate-bounce">
                                <i class="fa-solid fa-circle-notch animate-spin"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-slate-800 dark:text-white">Verification & Setup</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">Average wait time: &lt; 24 hours</p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-3.5 opacity-40">
                            <div class="h-6 w-6 rounded-full bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400 flex items-center justify-center text-[10px] font-black">
                                <i class="fa-solid fa-store"></i>
                            </div>
                            <div class="text-xs">
                                <h4 class="font-bold text-slate-650 dark:text-slate-350">Marketplace Launch</h4>
                                <p class="text-[10px] text-slate-400 font-semibold">Publish listing directory</p>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- Rejected Icon -->
                    <div class="inline-flex items-center justify-center h-24 w-24 rounded-[30px] bg-red-50 dark:bg-red-950/40 text-red-500 shadow-lg border border-red-100 dark:border-red-900/30">
                        <i class="fa-solid fa-circle-xmark text-4xl"></i>
                    </div>

                    <div class="space-y-3">
                        <span class="inline-block px-3 py-1 rounded-full bg-red-100 dark:bg-red-950/60 text-red-600 dark:text-red-400 text-[10px] font-black uppercase tracking-wider">Application Rejected</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-800 dark:text-white">Store Approval Unsuccessful</h1>
                        <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 font-medium leading-relaxed max-w-md mx-auto">
                            We regret to inform you that your application to open a merchant store on AgroMarket has been rejected. Please review our safety guidelines or reach out to support.
                        </p>
                    </div>
                @endif

                <div class="border-t dark:border-slate-700/60 pt-6 space-y-4">
                    <!-- Standard options to revert or ask for help -->
                    <div class="flex flex-col sm:flex-row justify-center items-center gap-3">
                        <!-- Switch back to Farmer -->
                        <form action="{{ route('dashboard.revert-farmer') }}" method="POST">
                            @csrf
                            <button type="submit" 
                                    class="w-full sm:w-auto px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-800 dark:text-white rounded-2xl font-bold text-xs uppercase tracking-wider transition-all active:scale-95 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-arrows-left-right"></i>
                                <span>Revert to Farmer Profile</span>
                            </button>
                        </form>

                        <!-- Go to Home Catalog -->
                        <a href="{{ route('home') }}" 
                           class="w-full sm:w-auto px-6 py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-md transition-all active:scale-95 flex items-center justify-center space-x-2">
                            <i class="fa-solid fa-house"></i>
                            <span>Browse Marketplace</span>
                        </a>
                    </div>
                    
                    <p class="text-[10px] text-slate-400 font-semibold">
                        Need immediate assistance? <a href="{{ route('contact') }}" class="text-emerald-500 hover:underline">Contact AgroMarket Merchant Support</a>
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
