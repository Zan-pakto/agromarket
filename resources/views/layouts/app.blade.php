<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- SEO Best Practices -->
        <title>@yield('title', 'AgroMarket - Online Agriculture Marketplace for Seeds and Tools')</title>
        <meta name="description" content="@yield('meta_description', 'AgroMarket helps farmers purchase premium agricultural seeds, organic fertilizers, precision farming tools, and heavy machinery from verified sellers.')">

        <!-- Modern Typography (Google Fonts) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Icons (FontAwesome) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Chart.js CDN for Analytics -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Alpine.js (Breeze includes Vite app.js which contains Alpine.js, but loading a fallback is good) -->
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Scripts & Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Dynamic Brand Themes Styles -->
        <style>
            :root {
                --color-primary: 34, 197, 94; /* tailwind green-500 */
                --color-primary-dark: 21, 128, 61; /* green-700 */
                --color-earth: 120, 113, 108; /* stone-500 */
                --color-earth-dark: 68, 64, 60; /* stone-700 */
                --color-accent: 234, 179, 8; /* yellow-500 */
                --font-primary: 'Outfit', sans-serif;
                --font-secondary: 'Plus Jakarta Sans', sans-serif;
            }
            body {
                font-family: var(--font-primary);
            }
            h1, h2, h3, h4, h5, h6 {
                font-family: var(--font-secondary);
            }
            .glassmorphism {
                background: rgba(255, 255, 255, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.3);
            }
            .dark .glassmorphism {
                background: rgba(15, 23, 42, 0.7);
                backdrop-filter: blur(12px);
                border: 1px solid rgba(255, 255, 255, 0.05);
            }
        </style>
    </head>
    <body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-emerald-50/30 dark:bg-slate-900 transition-colors duration-300 min-h-screen flex flex-col">
        <!-- Sticky header -->
        @include('components.navbar')

        <!-- Success/Error Toast Alerts -->
        @if(session('success') || session('error') || session('status'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-2 sm:translate-y-0 sm:translate-x-2"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed top-24 right-4 z-50 max-w-sm w-full bg-white dark:bg-slate-800 shadow-2xl rounded-2xl border-l-4 p-4 flex items-start space-x-3 @if(session('error')) border-red-500 @else border-emerald-500 @endif">
                <div class="flex-shrink-0">
                    @if(session('error'))
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    @else
                        <i class="fa-solid fa-circle-check text-emerald-500 text-xl"></i>
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-slate-900 dark:text-white text-sm">
                        @if(session('error')) Attention Required @else Success @endif
                    </h3>
                    <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">
                        {{ session('success') ?? session('error') ?? session('status') }}
                    </p>
                </div>
                <button @click="show = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                    <i class="fa-solid fa-xmark text-sm"></i>
                </button>
            </div>
        @endif

        <!-- Main Page Layout -->
        <div class="flex-grow pt-20">
            @yield('content')
        </div>

        <!-- Elegant Footer -->
        @include('components.footer')

        <!-- Smooth Theme Script -->
        <script>
            // Initialize dark mode if saved in local storage
            if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
        @yield('scripts')
    </body>
</html>
