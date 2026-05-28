@extends('layouts.app')

@section('title', 'Farmer Dashboard - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen" x-data="{ currentTab: 'overview' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header Profile Banner -->
        <div class="bg-gradient-to-br from-emerald-600 to-green-500 rounded-[40px] shadow-2xl p-8 sm:p-12 text-white mb-12 relative overflow-hidden text-left">
            <div class="absolute -top-12 -left-12 w-64 h-64 bg-white/5 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-white/5 rounded-full blur-xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6">
                <!-- Avatar -->
                <div class="h-20 w-20 sm:h-24 sm:w-24 bg-white/20 backdrop-blur-md rounded-[30px] p-1 shadow-xl flex-shrink-0">
                    <img class="h-full w-full rounded-[25px] object-cover" 
                         src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=047857&bold=true&size=150" 
                         alt="">
                </div>
                <!-- Profile details -->
                <div class="space-y-2 flex-grow text-center sm:text-left">
                    <span class="inline-block px-3 py-1 rounded-full bg-white/20 text-xs font-bold uppercase tracking-wider">Farmer Workspace</span>
                    <h1 class="text-2xl sm:text-3xl font-extrabold">{{ $user->name }}</h1>
                    <p class="text-xs sm:text-sm text-slate-100 font-semibold leading-relaxed">
                        <i class="fa-solid fa-envelope mr-1.5 opacity-80"></i> {{ $user->email }}
                        @if($user->phone) <span class="mx-2">|</span> <i class="fa-solid fa-phone mr-1.5 opacity-80"></i> {{ $user->phone }} @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Dashboard Workspace Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Navigation Tabs list -->
            <nav class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-4 flex flex-col space-y-1">
                <button @click="currentTab = 'overview'" 
                        :class="currentTab === 'overview' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-columns w-5 text-center"></i>
                    <span>Overview Panel</span>
                </button>
                
                <button @click="currentTab = 'orders'" 
                        :class="currentTab === 'orders' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-receipt w-5 text-center"></i>
                    <span>Purchase History</span>
                </button>

                <button @click="currentTab = 'wishlist'" 
                        :class="currentTab === 'wishlist' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-heart w-5 text-center"></i>
                    <span>Saved Wishlist</span>
                </button>

                <button @click="currentTab = 'settings'" 
                        :class="currentTab === 'settings' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-user-gear w-5 text-center"></i>
                    <span>Account Settings</span>
                </button>

                <button @click="currentTab = 'become-seller'" 
                        :class="currentTab === 'become-seller' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-store w-5 text-center"></i>
                    <span>Become a Seller</span>
                </button>
            </nav>

            <!-- Dashboard Content Screens -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- Tab: Overview -->
                <div x-show="currentTab === 'overview'" class="space-y-8">
                    <!-- Notifications box & Recent Order Summary -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                        
                        <!-- Notifications List -->
                        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 space-y-6 text-left" id="notifications">
                            <div class="flex justify-between items-center pb-3 border-b dark:border-slate-700/60">
                                <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">In-App Notifications</h3>
                                <button onclick="
                                            fetch('{{ route('dashboard.notifications.read') }}', {
                                                method: 'POST',
                                                headers: {
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                    'Accept': 'application/json'
                                                }
                                            }).then(() => window.location.reload())
                                        " 
                                        class="text-[10px] font-bold text-slate-400 hover:text-emerald-500 transition-colors">Mark All Read</button>
                            </div>
                            
                            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 divide-y divide-slate-50 dark:divide-slate-700/50">
                                @forelse($notifications as $notif)
                                    <div class="pt-3.5 first:pt-0 flex items-start space-x-3 text-xs">
                                        <div class="p-2 rounded-xl shrink-0 {{ $notif->is_read ? 'bg-slate-100 dark:bg-slate-900 text-slate-400' : 'bg-emerald-50 dark:bg-emerald-950/20 text-emerald-500' }}">
                                            <i class="fa-solid {{ $notif->type === 'order' ? 'fa-box' : 'fa-bell' }}"></i>
                                        </div>
                                        <div class="flex-grow">
                                            <h4 class="font-bold {{ $notif->is_read ? 'text-slate-600 dark:text-slate-400' : 'text-slate-800 dark:text-white' }}">{{ $notif->title }}</h4>
                                            <p class="text-slate-500 dark:text-slate-400 text-[11px] leading-relaxed mt-0.5">{{ $notif->message }}</p>
                                            <span class="text-[9px] text-slate-400 font-bold block mt-1">{{ $notif->created_at ? $notif->created_at->diffForHumans() : 'Just now' }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-bell-slash text-slate-300 text-2xl mb-2"></i>
                                        <p class="text-xs text-slate-400 font-semibold">No recent notifications.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <!-- Mini Recent purchases list -->
                        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 space-y-6 text-left">
                            <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider pb-3 border-b dark:border-slate-700/60">Recent Purchases</h3>
                            
                            <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2 divide-y divide-slate-50 dark:divide-slate-700/50">
                                @forelse($recentOrders as $ord)
                                    <div class="pt-3.5 first:pt-0 flex justify-between items-center text-xs">
                                        <div class="text-left space-y-1">
                                            <h4 class="font-bold text-slate-800 dark:text-white">{{ $ord->tracking_number }}</h4>
                                            <p class="text-[10px] text-slate-400 font-semibold">Total: ${{ number_format($ord->total_amount, 2) }} | Status: <strong class="text-emerald-500 uppercase">{{ $ord->order_status }}</strong></p>
                                        </div>
                                        <a href="{{ route('orders.show', $ord->id) }}" class="text-[10px] bg-slate-100 hover:bg-emerald-50 text-slate-700 hover:text-emerald-600 dark:bg-slate-900 dark:hover:bg-slate-750 dark:text-slate-200 px-3 py-1.5 rounded-xl font-bold transition-all">Details</a>
                                    </div>
                                @empty
                                    <div class="text-center py-8">
                                        <i class="fa-solid fa-receipt text-slate-300 text-2xl mb-2"></i>
                                        <p class="text-xs text-slate-400 font-semibold">No purchase history yet.</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>

                    <!-- Recommended products -->
                    <div class="space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3 text-left">Recommended Seeds & Tools</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                            @foreach($recommended as $prod)
                                <x-product-card :product="$prod" />
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Tab: Orders History List -->
                <div x-show="currentTab === 'orders'" class="space-y-6" style="display: none;">
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                    <th class="px-6 py-4">Order Code</th>
                                    <th class="px-6 py-4">Placement Date</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right">Payment</th>
                                    <th class="px-6 py-4 text-right">Total Amount</th>
                                    <th class="px-6 py-4 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                                @forelse($recentOrders as $ord)
                                    <tr>
                                        <td class="px-6 py-5 font-bold text-slate-800 dark:text-white">
                                            {{ $ord->tracking_number }}
                                        </td>
                                        <td class="px-6 py-5 font-semibold text-slate-500 dark:text-slate-400">
                                            {{ $ord->created_at ? $ord->created_at->format('M d, Y') : now()->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-5 text-center">
                                            <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-lg border {{ $ord->getStatusColorClass() }}">
                                                {{ $ord->order_status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-5 text-right font-bold uppercase tracking-wide text-slate-600 dark:text-slate-300">
                                            {{ $ord->payment_method }} ({{ $ord->payment_status }})
                                        </td>
                                        <td class="px-6 py-5 text-right font-black text-slate-900 dark:text-white">
                                            ${{ number_format($ord->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-5 text-center flex items-center justify-center space-x-2">
                                            <a href="{{ route('orders.show', $ord->id) }}" class="p-2 hover:bg-emerald-50 dark:hover:bg-slate-950/20 text-emerald-500 rounded-xl transition-all" title="View invoice details">
                                                <i class="fa-solid fa-file-invoice text-sm"></i>
                                            </a>
                                            <a href="{{ route('orders.track', $ord->id) }}" class="p-2 hover:bg-emerald-50 dark:hover:bg-slate-950/20 text-emerald-500 rounded-xl transition-all" title="Track shipment status">
                                                <i class="fa-solid fa-truck-ramp-box text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                            No purchases found in database. Discover our catalog to place your first order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab: Wishlist Items -->
                <div x-show="currentTab === 'wishlist'" class="space-y-6" style="display: none;">
                    @if($wishlistItems->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            @foreach($wishlistItems as $prod)
                                <x-product-card :product="$prod" />
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-16 bg-white dark:bg-slate-800 rounded-[35px] border dark:border-slate-700/60 shadow-xl space-y-4">
                            <i class="fa-solid fa-heart-crack text-slate-300 text-3xl mb-2"></i>
                            <p class="text-xs font-semibold text-slate-400">Your wishlist is currently empty. Bookmark products to display them here.</p>
                        </div>
                    @endif
                </div>

                <!-- Tab: Account Profile settings -->
                <div x-show="currentTab === 'settings'" class="space-y-6" style="display: none;">
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6 text-left">
                        <h3 class="text-base font-bold text-slate-800 dark:text-white border-l-3 border-emerald-500 pl-3">Update Profile Credentials</h3>
                        
                        <form action="{{ route('dashboard.profile') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Full Name</label>
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                                </div>
                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Phone Line</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold" placeholder="+1 (555) 000-0000">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Homestead Physical Address</label>
                                <textarea name="address" rows="3" placeholder="Street number, farm sector, state code..."
                                          class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <hr class="dark:border-slate-700">

                            <!-- Password Update -->
                            <h4 class="text-xs font-bold text-slate-800 dark:text-white">Change Security Password (Optional)</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">New Password</label>
                                    <input type="password" name="password" 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Confirm Password</label>
                                    <input type="password" name="password_confirmation" 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white">
                                </div>
                            </div>

                            <button type="submit" 
                                    class="px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-md transition-all active:scale-95">
                                Save Profile Credentials
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Tab: Become a Seller -->
                <div x-show="currentTab === 'become-seller'" class="space-y-6" style="display: none;">
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 lg:p-10 space-y-6 text-left relative overflow-hidden">
                        <div class="absolute -top-12 -left-12 w-64 h-64 bg-emerald-500/5 rounded-full blur-xl"></div>
                        <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-emerald-500/5 rounded-full blur-xl"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6 pb-6 border-b dark:border-slate-700/60">
                            <div class="space-y-2">
                                <span class="inline-block px-3 py-1 rounded-full bg-emerald-100 dark:bg-emerald-950/45 text-emerald-600 dark:text-emerald-400 text-[10px] font-bold uppercase tracking-wider">Opportunity awaits</span>
                                <h3 class="text-xl font-black text-slate-800 dark:text-white">Start Selling on AgroMarket</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 font-medium">Convert your profile to a Seller Account and reach thousands of buyers daily.</p>
                            </div>
                            <div class="shrink-0 text-emerald-500 text-5xl">
                                <i class="fa-solid fa-store"></i>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                            <div class="bg-slate-50 dark:bg-slate-900/40 p-5 rounded-2xl border dark:border-slate-700/50 space-y-2">
                                <span class="text-emerald-500 font-bold"><i class="fa-solid fa-chart-pie mr-2"></i>Lower Fees</span>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-semibold">Keep 95% of your earnings with flat transparent platform commission rates.</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/40 p-5 rounded-2xl border dark:border-slate-700/50 space-y-2">
                                <span class="text-emerald-500 font-bold"><i class="fa-solid fa-truck-fast mr-2"></i>Agro Logistics</span>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-semibold">Get shipping and inventory support integrated with local warehouses.</p>
                            </div>
                            <div class="bg-slate-50 dark:bg-slate-900/40 p-5 rounded-2xl border dark:border-slate-700/50 space-y-2">
                                <span class="text-emerald-500 font-bold"><i class="fa-solid fa-shield-halved mr-2"></i>Instant Trust</span>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-semibold">Approved sellers receive verification badges to build buyer confidence.</p>
                            </div>
                        </div>

                        <form action="{{ route('dashboard.become-seller') }}" method="POST" class="space-y-6 relative z-10">
                            @csrf
                            
                            <div class="bg-amber-50 dark:bg-amber-950/20 border border-amber-200 dark:border-amber-900/50 rounded-2xl p-4 flex items-start space-x-3 text-xs text-amber-800 dark:text-amber-300">
                                <i class="fa-solid fa-triangle-exclamation text-base shrink-0 mt-0.5"></i>
                                <div class="leading-relaxed">
                                    <strong class="font-bold block mb-1">Important: Seller Application Guidelines</strong>
                                    Upon submission, your account will be placed under administrator review. You will temporarily lose farmer dashboard capabilities while your application is processed. To be approved, please ensure your phone and physical address are fully populated and accurate.
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Phone -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Business Phone Line</label>
                                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" required 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold" placeholder="+1 (555) 000-0000">
                                </div>
                                <!-- Store Name / Business Name -->
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Store / Business Name</label>
                                    <input type="text" name="store_name" value="{{ old('store_name', $user->name . ' Store') }}" required 
                                           class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Store Physical / Dispatch Address</label>
                                <textarea name="address" rows="3" placeholder="Full street address, district, state..." required
                                          class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div class="flex items-center justify-end pt-4">
                                <button type="submit" 
                                        class="px-8 py-4 bg-emerald-500 hover:bg-emerald-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-lg transition-all active:scale-95 flex items-center space-x-2">
                                    <i class="fa-solid fa-paper-plane"></i>
                                    <span>Submit Application for Approval</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection
