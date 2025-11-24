<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hiyoucan') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fdf2f8', 100: '#fce7f3', 500: '#ec4899', 600: '#db2777', 900: '#831843',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-pink-50 text-gray-800 flex flex-col min-h-screen">
    
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 top-0 border-b border-pink-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                
                <div class="flex items-center gap-8">
                    <a href="/" class="text-2xl font-extrabold bg-gradient-to-r from-brand-500 to-purple-500 text-transparent bg-clip-text hover:opacity-80 transition">
                        Hiyoucan.
                    </a>
                    
                    <div class="hidden md:flex space-x-6">
                        <a href="/" class="text-sm font-medium text-gray-500 hover:text-brand-600 transition {{ request()->is('/') ? 'text-brand-600 font-bold' : '' }}">
                            Home
                        </a>
                        <a href="{{ route('shop.index') }}" class="text-sm font-medium text-gray-500 hover:text-brand-600 transition {{ request()->routeIs('shop.*') ? 'text-brand-600 font-bold' : '' }}">
                            Shop Skincare
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4 md:gap-6">
                    
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="text-gray-400 hover:text-brand-500 transition relative" title="Wishlist">
                            <span class="text-xl">♥</span>
                        </a>

                        <a href="{{ route('cart.index') }}" class="text-gray-400 hover:text-brand-500 transition flex items-center gap-1 relative">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            {{-- <span class="absolute -top-1 -right-1 bg-brand-500 text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center">2</span> --}}
                        </a>

                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open" class="flex items-center gap-2 text-sm font-bold text-gray-700 hover:text-brand-600 transition">
                                <span>Hi, {{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>

                            <div x-show="open" @click.away="open = false" x-cloak class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl py-2 border border-gray-100 z-50">
                                
                                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'seller')
                                    <a href="/admin" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-brand-600">
                                        {{ Auth::user()->role === 'admin' ? 'Admin Panel' : 'Seller Dashboard' }}
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-brand-600">
                                        Dashboard Saya
                                    </a>
                                @endif

                                <div class="border-t border-gray-100 my-1"></div>

                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 font-bold">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>

                    @else
                        <div class="flex items-center gap-3">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-brand-600 transition">
                                Log in
                            </a>
                            <a href="{{ route('register') }}" class="text-sm font-bold bg-brand-500 text-white px-5 py-2.5 rounded-full hover:bg-brand-600 shadow-lg shadow-brand-500/30 transition">
                                Register
                            </a>
                        </div>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12 flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-white border-t border-gray-100 py-8 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500 text-sm">
            &copy; {{ date('Y') }} <strong>Hiyoucan Skincare</strong>. All rights reserved. <br>
            Dibuat untuk Tugas Individual Project 1.
        </div>
    </footer>

</body>
</html>