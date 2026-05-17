@extends('layouts.app')

@section('title', 'Admin Platform Command - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen" x-data="{ currentTab: 'overview' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header banner -->
        <div class="bg-gradient-to-br from-slate-900 to-amber-950 rounded-[40px] shadow-2xl p-8 sm:p-12 text-white mb-12 relative overflow-hidden text-left">
            <div class="absolute -top-12 -left-12 w-64 h-64 bg-amber-500/5 rounded-full blur-xl"></div>
            <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-amber-500/5 rounded-full blur-xl"></div>

            <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
                <div class="flex items-center space-x-4">
                    <div class="bg-amber-500 text-white p-3.5 rounded-2xl shadow-lg">
                        <i class="fa-solid fa-screwdriver-wrench text-xl"></i>
                    </div>
                    <div>
                        <span class="inline-block px-2.5 py-0.5 rounded-md bg-amber-500/20 text-amber-400 text-[10px] font-black uppercase tracking-wider">Super Administrator</span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold mt-1">Platform Admin Command</h1>
                        <p class="text-xs text-slate-400 font-semibold mt-0.5">Control center for AgroMarket operations</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytical Counters -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-12 text-left">
            <!-- Counter 1 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Gross Revenue</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">${{ number_format($analytics['total_sales'], 2) }}</p>
                <span class="text-[9px] text-emerald-500 font-bold block">Combined platform sales</span>
            </div>

            <!-- Counter 2 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Registered Users</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $analytics['total_users'] }}</p>
                <span class="text-[9px] text-emerald-500 font-bold block"><a href="{{ route('dashboard.admin.users') }}" class="underline">Manage User Accounts</a></span>
            </div>

            <!-- Counter 3 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Total Listings</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $analytics['total_products'] }}</p>
                <span class="text-[9px] text-slate-400 font-bold block">Seeds & tools catalogue size</span>
            </div>

            <!-- Counter 4 -->
            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-6 space-y-2">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block">Pending Stores</span>
                <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $sellersPending->count() }}</p>
                <span class="text-[9px] text-red-500 font-bold block">Sellers awaiting approval</span>
            </div>
        </div>

        <!-- Primary Grid Layout -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <!-- Side Navigation links -->
            <aside class="lg:col-span-3 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-sm p-4 flex flex-col space-y-1">
                <button @click="currentTab = 'overview'" 
                        :class="currentTab === 'overview' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-chart-line w-5 text-center"></i>
                    <span>Marketplace overview</span>
                </button>

                <button @click="currentTab = 'sellers'" 
                        :class="currentTab === 'sellers' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-user-clock w-5 text-center"></i>
                    <span>Seller Approvals Queue</span>
                </button>

                <button @click="currentTab = 'categories'" 
                        :class="currentTab === 'categories' ? 'bg-emerald-500 text-white font-bold' : 'text-slate-600 dark:text-slate-300 hover:bg-emerald-50 dark:hover:bg-slate-700/50'"
                        class="w-full text-left px-4 py-3 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center space-x-2.5">
                    <i class="fa-solid fa-tags w-5 text-center"></i>
                    <span>Product Categories</span>
                </button>
            </aside>

            <!-- Main Interactive Containers -->
            <div class="lg:col-span-9 space-y-8">
                
                <!-- Tab Overview: platform charts -->
                <div x-show="currentTab === 'overview'" class="space-y-8">
                    <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-8 text-left space-y-6">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-wider">Gross Platform monthly sales</h3>
                        <div class="relative h-72 w-full">
                            <canvas id="adminSalesChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Tab Sellers: pending approvals -->
                <div x-show="currentTab === 'sellers'" class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden" style="display: none;">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                <th class="px-6 py-4">Seller Store Details</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Telephone</th>
                                <th class="px-6 py-4">Warehouse Address</th>
                                <th class="px-6 py-4 text-center">Approve / Reject</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                            @forelse($sellersPending as $sel)
                                <tr>
                                    <td class="px-6 py-5 font-bold text-slate-800 dark:text-white text-left">
                                        {{ $sel->name }}
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-500 dark:text-slate-450">
                                        {{ $sel->email }}
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-500 dark:text-slate-450">
                                        {{ $sel->phone ?? 'Not provided' }}
                                    </td>
                                    <td class="px-6 py-5 font-semibold text-slate-550 dark:text-slate-400 max-w-xs leading-relaxed text-left">
                                        {{ $sel->address ?? 'Not provided' }}
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center justify-center space-x-2">
                                            <!-- Approve -->
                                            <form action="{{ route('dashboard.admin.seller.toggle', $sel->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="px-3 py-1.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-lg font-bold text-[10px] uppercase shadow transition-all">Approve</button>
                                            </form>
                                            <!-- Reject -->
                                            <form action="{{ route('dashboard.admin.seller.toggle', $sel->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg font-bold text-[10px] uppercase shadow transition-all">Reject</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                        No pending seller registrations found in database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Tab Categories: list and add category form -->
                <div x-show="currentTab === 'categories'" class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start" style="display: none;">
                    <!-- Left: list categories -->
                    <div class="md:col-span-7 bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                                    <th class="px-6 py-4">Icon & Name</th>
                                    <th class="px-6 py-4">Slug Code</th>
                                    <th class="px-6 py-4 text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                                @foreach($categories as $cat)
                                    <tr>
                                        <td class="px-6 py-4 flex items-center space-x-3 text-left">
                                            <div class="p-2 rounded-lg bg-emerald-50 dark:bg-slate-900 text-emerald-500">
                                                <i class="fa-solid {{ $cat->icon ?? 'fa-seedling' }}"></i>
                                            </div>
                                            <span class="font-bold text-slate-800 dark:text-white">{{ $cat->name }}</span>
                                        </td>
                                        <td class="px-6 py-4 font-semibold text-slate-400">
                                            {{ $cat->slug }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <form action="{{ route('dashboard.admin.category.delete', $cat->id) }}" method="POST" onsubmit="return confirm('Warning! Deleting this category will delete all related products. Proceed?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-red-500 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition-all"><i class="fa-solid fa-trash-can"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Right: Add Category Form -->
                    <div class="md:col-span-5 bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl p-6 text-left space-y-4">
                        <h3 class="text-sm font-bold text-slate-800 dark:text-white pb-2 border-b dark:border-slate-700/60">Create Product Category</h3>
                        
                        <form action="{{ route('dashboard.admin.category.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Category Name</label>
                                <input type="text" name="name" required placeholder="e.g. Bio Pesticides" 
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Icon FontAwesome Class</label>
                                <input type="text" name="icon" required placeholder="e.g. fa-biohazard" 
                                       class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2.5 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold">
                            </div>

                            <div class="space-y-1">
                                <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Description</label>
                                <textarea name="description" rows="3" placeholder="Category bio input description..." 
                                          class="w-full text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-xl px-3 py-2 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-semibold"></textarea>
                            </div>

                            <button type="submit" 
                                    class="w-full py-3 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-xs uppercase shadow transition-all">
                                Create Category Division
                            </button>
                        </form>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('adminSalesChart');
        if (ctx) {
            // Retrieve Chart coordinate datasets dynamically from Laravel database counts
            const monthlyLabels = {!! json_encode(array_keys($analytics['monthly_sales'])) !!};
            const monthlyValues = {!! json_encode(array_values($analytics['monthly_sales'])) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: monthlyLabels,
                    datasets: [{
                        label: 'Gross Sales ($)',
                        data: monthlyValues,
                        borderColor: '#eab308', // amber-500
                        backgroundColor: 'rgba(234, 179, 8, 0.1)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#eab308',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(255, 255, 255, 0.05)' },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        },
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Outfit', size: 10 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
