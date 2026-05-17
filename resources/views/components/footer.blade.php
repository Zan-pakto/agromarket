<footer class="bg-slate-900 text-slate-300 dark:bg-slate-950 pt-16 pb-8 border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
            <!-- Col 1: About -->
            <div class="space-y-6">
                <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                    <div class="bg-emerald-500 text-white p-2.5 rounded-xl">
                        <i class="fa-solid fa-leaf text-md"></i>
                    </div>
                    <span class="text-xl font-extrabold text-white tracking-tight">
                        AgroMarket
                    </span>
                </a>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    AgroMarket is a premium peer-to-peer agriculture marketplace. We connect local farmers directly with trusted suppliers for organic seeds, rich fertilizers, precision irrigation kits, and heavy machinery, fostering sustainable farming.
                </p>
                <div class="flex space-x-4">
                    <a href="#" class="p-2 bg-slate-800 hover:bg-emerald-500 hover:text-white rounded-xl transition-all duration-300">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-emerald-500 hover:text-white rounded-xl transition-all duration-300">
                        <i class="fa-brands fa-twitter text-sm"></i>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-emerald-500 hover:text-white rounded-xl transition-all duration-300">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="#" class="p-2 bg-slate-800 hover:bg-emerald-500 hover:text-white rounded-xl transition-all duration-300">
                        <i class="fa-brands fa-youtube text-sm"></i>
                    </a>
                </div>
            </div>

            <!-- Col 2: Categories Links -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3">Marketplace</h3>
                <ul class="space-y-3 text-xs">
                    <li><a href="{{ route('shop.index') }}?category=1" class="hover:text-emerald-400 transition-colors">Premium Seeds</a></li>
                    <li><a href="{{ route('shop.index') }}?category=2" class="hover:text-emerald-400 transition-colors">Organic Fertilizers</a></li>
                    <li><a href="{{ route('shop.index') }}?category=3" class="hover:text-emerald-400 transition-colors">Farming Hand Tools</a></li>
                    <li><a href="{{ route('shop.index') }}?category=4" class="hover:text-emerald-400 transition-colors">Irrigation Equipment</a></li>
                    <li><a href="{{ route('shop.index') }}?category=5" class="hover:text-emerald-400 transition-colors">Tractors & Machinery</a></li>
                    <li><a href="{{ route('shop.index') }}?category=6" class="hover:text-emerald-400 transition-colors">Bio Organic Inputs</a></li>
                </ul>
            </div>

            <!-- Col 3: Company info -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3">Information</h3>
                <ul class="space-y-3 text-xs">
                    <li><a href="{{ route('about') }}" class="hover:text-emerald-400 transition-colors">About AgroMarket</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-emerald-400 transition-colors">Get In Touch</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-emerald-400 transition-colors">Frequently Asked Questions</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-emerald-400 transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            <!-- Col 4: Newsletter -->
            <div class="space-y-6">
                <h3 class="text-sm font-bold text-white uppercase tracking-wider border-l-3 border-emerald-500 pl-3">Newsletter</h3>
                <p class="text-xs text-slate-400 leading-relaxed font-medium">
                    Subscribe to receive crop growing guidelines, exclusive supplier discount codes, and fresh product launches directly in your inbox.
                </p>
                <form x-data="{ email: '', success: false }" @submit.prevent="success = true" class="space-y-3">
                    <div class="relative">
                        <input x-model="email" type="email" placeholder="Your Email Address" required 
                               class="w-full text-xs bg-slate-800 border border-slate-700 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-emerald-500 transition-colors placeholder-slate-500">
                    </div>
                    <button type="submit" 
                            class="w-full py-3 bg-gradient-to-r from-emerald-600 to-green-500 hover:from-emerald-500 hover:to-green-400 text-white font-bold text-xs rounded-xl shadow-lg shadow-emerald-950/20 active:scale-98 transition-all">
                        Subscribe Now
                    </button>
                    <p x-show="success" x-transition class="text-[10px] text-emerald-400 font-semibold" style="display: none;">
                        <i class="fa-solid fa-circle-check mr-1"></i> Subscription successful! Check your inbox soon.
                    </p>
                </form>
            </div>
        </div>

        <hr class="border-slate-800 my-12">

        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 text-[11px] text-slate-500 font-semibold">
            <p>&copy; {{ date('Y') }} AgroMarket Inc. All rights reserved. Empowering Farmers, Globally.</p>
            <div class="flex space-x-6">
                <a href="#" class="hover:text-slate-400">Security</a>
                <a href="#" class="hover:text-slate-400">Sitemap</a>
                <a href="#" class="hover:text-slate-400">Supplier Policies</a>
            </div>
        </div>
    </div>
</footer>
