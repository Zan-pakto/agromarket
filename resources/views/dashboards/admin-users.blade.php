@extends('layouts.app')

@section('title', 'Manage User Accounts - AgroMarket')

@section('content')
<div class="py-12 bg-slate-50/50 dark:bg-slate-900/10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        
        <!-- Back Link -->
        <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 text-xs font-bold text-slate-500 hover:text-emerald-500 transition-colors">
            <i class="fa-solid fa-arrow-left"></i>
            <span>Return to Admin Console</span>
        </a>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6">
            <div class="text-left">
                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white">Manage User Accounts</h1>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">Review registrations, adjust privileges, or toggle seller approvals.</p>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700/60 shadow-xl p-6">
            <form action="{{ route('dashboard.admin.users') }}" method="GET" class="flex flex-col sm:flex-row gap-4 items-stretch">
                <!-- Search input -->
                <div class="flex-grow flex items-center bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 text-slate-400 focus-within:text-emerald-500">
                    <i class="fa-solid fa-magnifying-glass text-sm mr-2.5"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email..." 
                           class="w-full bg-transparent border-none text-xs text-slate-800 dark:text-white focus:outline-none placeholder-slate-400 font-semibold">
                </div>

                <!-- Role Filter -->
                <select name="role" onchange="this.form.submit()" 
                        class="text-xs bg-slate-50 dark:bg-slate-900 border dark:border-slate-700 rounded-2xl px-4 py-3 focus:outline-none focus:border-emerald-500 text-slate-800 dark:text-white font-bold">
                    <option value="">All Roles</option>
                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="farmer" {{ request('role') == 'farmer' ? 'selected' : '' }}>Farmer</option>
                    <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>Seller</option>
                </select>

                <button type="submit" 
                        class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-2xl font-bold text-xs uppercase shadow-sm">
                    Search Users
                </button>
            </form>
        </div>

        <!-- User Accounts Table -->
        <div class="bg-white dark:bg-slate-800 rounded-[35px] border border-slate-100 dark:border-slate-700/60 shadow-xl overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b dark:border-slate-700 text-slate-400 font-bold text-[9px] uppercase tracking-widest bg-slate-50 dark:bg-slate-900/30">
                        <th class="px-6 py-4">User Details</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4 text-center">Role Tag</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4">Phone Line</th>
                        <th class="px-6 py-4">Registered Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-xs">
                    @forelse($users as $usr)
                        <tr>
                            <!-- details -->
                            <td class="px-6 py-4 flex items-center space-x-3 text-left">
                                <img class="h-8 w-8 rounded-lg object-cover ring-2 ring-emerald-500/10" 
                                     src="https://ui-avatars.com/api/?name={{ urlencode($usr->name) }}&background=10b981&color=fff&bold=true" 
                                     alt="">
                                <span class="font-bold text-slate-800 dark:text-white">{{ $usr->name }}</span>
                            </td>
                            <!-- email -->
                            <td class="px-6 py-4 font-semibold text-slate-500 dark:text-slate-400">
                                {{ $usr->email }}
                            </td>
                            <!-- Role -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 text-[10px] font-black uppercase rounded-lg border 
                                             {{ $usr->role === 'admin' ? 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-950/20 dark:text-amber-400' : ($usr->role === 'seller' ? 'bg-indigo-50 text-indigo-700 border-indigo-200 dark:bg-indigo-950/20 dark:text-indigo-400' : 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/20 dark:text-emerald-400') }}">
                                    {{ $usr->role }}
                                </span>
                            </td>
                            <!-- Status -->
                            <td class="px-6 py-4 text-center">
                                <span class="px-2.5 py-1 text-[10px] font-extrabold uppercase rounded-lg border 
                                             {{ $usr->status === 'approved' ? 'bg-green-100 text-green-800 border-green-200 dark:bg-green-950/40 dark:text-green-400' : ($usr->status === 'pending' ? 'bg-yellow-100 text-yellow-800 border-yellow-200 dark:bg-yellow-950/40 dark:text-yellow-400' : 'bg-red-100 text-red-800 border-red-200 dark:bg-red-950/40 dark:text-red-400') }}">
                                    {{ $usr->status }}
                                </span>
                            </td>
                            <!-- phone -->
                            <td class="px-6 py-4 font-semibold text-slate-500 dark:text-slate-400">
                                {{ $usr->phone ?? 'Not provided' }}
                            </td>
                            <!-- Date -->
                            <td class="px-6 py-4 font-semibold text-slate-400">
                                {{ $usr->created_at ? $usr->created_at->format('M d, Y') : now()->format('M d, Y') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-slate-400 font-semibold">
                                No user accounts registered.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div>
            {{ $users->links() }}
        </div>

    </div>
</div>
@endsection
