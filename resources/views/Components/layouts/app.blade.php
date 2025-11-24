<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hiyoucan') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Outfit"', 'sans-serif'] },
                    colors: {
                        // Palet Warna Premium (Rose Gold & Charcoal)
                        primary: { 50: '#fff1f2', 100: '#ffe4e6', 500: '#f43f5e', 600: '#e11d48', 900: '#881337' },
                        neutral: { 800: '#27272a', 900: '#18181b' }
                    }
                }
            }
        }
    </script>
    <style>[x-cloak] { display: none !important; }</style>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-sans antialiased bg-[#FAFAFA] text-neutral-800 flex flex-col min-h-screen" 
      x-data="toastHandler()">

    <div class="fixed top-24 right-5 z-[100] flex flex-col gap-3 pointer-events-none">
        <template x-for="toast in toasts" :key="toast.id">
            <div x-show="toast.visible" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="translate-x-full opacity-0"
                 x-transition:enter-end="translate-x-0 opacity-100"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0 translate-x-full"
                 class="pointer-events-auto flex items-center w-80 p-4 bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border border-gray-100 backdrop-blur-sm">
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                     :class="toast.type === 'success' ? 'bg-green-50 text-green-500' : 'bg-red-50 text-red-500'">
                    <span x-text="toast.type === 'success' ? '✓' : '!'" class="font-bold text-lg"></span>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-bold text-gray-900" x-text="toast.type === 'success' ? 'Berhasil!' : 'Ups!'"></p>
                    <p class="text-sm text-gray-500" x-text="toast.message"></p>
                </div>
            </div>
        </template>
    </div>

    <nav class="fixed w-full z-50 top-0 bg-white/80 backdrop-blur-xl border-b border-gray-100 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="/" class="text-2xl font-extrabold tracking-tight text-neutral-900 hover:text-primary-600 transition">
                    Hiyoucan<span class="text-primary-500">.</span>
                </a>

                <div class="hidden md:flex space-x-8 items-center">
                    <a href="/" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Home</a>
                    <a href="{{ route('shop.index') }}" class="text-sm font-medium text-gray-500 hover:text-primary-600 transition">Collection</a>
                </div>

                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('wishlist.index') }}" class="relative p-2.5 text-gray-400 hover:text-primary-500 hover:bg-primary-50 rounded-full transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </a>
                        <a href="{{ route('cart.index') }}" class="relative p-2.5 text-gray-400 hover:text-primary-500 hover:bg-primary-50 rounded-full transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </a>

                        <div x-data="{ open: false }" class="relative ml-2">
                            <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ffe4e6&color=e11d48' }}" 
                                     class="w-9 h-9 rounded-full object-cover border border-gray-200 shadow-sm">
                            </button>
                            <div x-show="open" @click.away="open = false" x-cloak 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute right-0 mt-3 w-56 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50">
                                <div class="px-4 py-3 border-b border-gray-50 mb-1">
                                    <p class="text-sm font-bold text-gray-900">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                @if(in_array(Auth::user()->role, ['admin', 'seller']))
                                    <a href="/admin" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600">Dashboard Panel</a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600">Pesanan Saya</a>
                                @endif
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-600 hover:bg-gray-50 hover:text-primary-600">Settings & Profil</a>
                                <div class="border-t border-gray-50 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-red-50 font-bold">Keluar</button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-600 hover:text-primary-600 px-4">Masuk</a>
                        <a href="{{ route('register') }}" class="text-sm font-bold bg-primary-600 text-white px-6 py-2.5 rounded-full hover:bg-primary-700 shadow-lg shadow-primary-500/30 transition">Daftar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 flex-grow">
        {{ $slot }}
    </main>

    <footer class="bg-neutral-900 text-white pt-20 pb-10 mt-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-neutral-800 pb-12">
                <div class="col-span-1 md:col-span-1">
                    <h3 class="text-2xl font-extrabold tracking-tight mb-4 text-white">Hiyoucan.</h3>
                    <p class="text-neutral-400 text-sm leading-relaxed">
                        Platform e-commerce skincare terpercaya dengan kurasi produk terbaik untuk kulit Indonesia.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-6">Eksplorasi</h4>
                    <ul class="space-y-3 text-sm text-neutral-400">
                        <li><a href="#" class="hover:text-primary-500 transition">Katalog Produk</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Promo Spesial</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Tentang Kami</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-6">Layanan</h4>
                    <ul class="space-y-3 text-sm text-neutral-400">
                        <li><a href="#" class="hover:text-primary-500 transition">Bantuan</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Konfirmasi Pembayaran</a></li>
                        <li><a href="#" class="hover:text-primary-500 transition">Pengembalian</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-lg mb-6">Berlangganan</h4>
                    <div class="flex bg-neutral-800 rounded-xl p-1">
                        <input type="email" placeholder="Email Anda" class="bg-transparent border-none text-sm text-white w-full focus:ring-0 px-4">
                        <button class="bg-primary-600 px-4 py-2 rounded-lg font-bold text-sm hover:bg-primary-500 transition">Join</button>
                    </div>
                </div>
            </div>
            <div class="pt-8 text-center text-neutral-600 text-xs">
                &copy; {{ date('Y') }} Hiyoucan Skincare. All rights reserved.
            </div>
        </div>
    </footer>

    <script>
        function toastHandler() {
            return {
                toasts: [],
                add(message, type = 'success') {
                    const id = Date.now();
                    this.toasts.push({ id, message, type, visible: true });
                    setTimeout(() => { this.remove(id) }, 4000);
                },
                remove(id) {
                    const index = this.toasts.findIndex(t => t.id === id);
                    if (index > -1) this.toasts[index].visible = false;
                }
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                document.querySelector('[x-data]').__x.$data.add("{{ session('success') }}", 'success');
            @endif
            @if(session('error'))
                document.querySelector('[x-data]').__x.$data.add("{{ session('error') }}", 'error');
            @endif
        });
    </script>
</body>
</html>