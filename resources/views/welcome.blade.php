<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hiyoucan - Glow Your Way</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fdf2f8', // Pink lembut background
                            100: '#fce7f3',
                            500: '#ec4899', // Pink tombol
                            600: '#db2777',
                            900: '#831843', // Text gelap
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="antialiased bg-brand-50 text-gray-800 font-sans">

    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 top-0 border-b border-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center gap-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-brand-500 to-purple-500 text-transparent bg-clip-text">
                        Hiyoucan.
                    </span>
                </div>

                <div class="hidden md:flex space-x-8">
                    <a href="#" class="text-gray-500 hover:text-brand-600 transition font-medium">Home</a>
                    <a href="{{ route('shop.index') }}" class="text-gray-500 hover:text-brand-600 transition font-medium">Shop Skincare</a>
                    <a href="#" class="text-gray-500 hover:text-brand-600 transition font-medium">About Us</a>
                </div>

                <div class="flex items-center space-x-4">
                    @auth
                        @if(Auth::user()->role === 'admin')
                            <a href="/admin" class="text-sm font-semibold text-brand-600 hover:text-brand-900">Admin Panel</a>
                        @elseif(Auth::user()->role === 'seller')
                            <a href="/admin" class="text-sm font-semibold text-brand-600 hover:text-brand-900">Seller Dashboard</a>
                        @else
                           <a href="/dashboard" class="text-sm font-semibold text-brand-600 hover:text-brand-900">My Dashboard</a>
                        @endif
                        
                        <button class="relative p-2 text-gray-400 hover:text-brand-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-brand-500 rounded-full">0</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="text-brand-600 hover:text-brand-800 font-medium text-sm">Log in</a>
                        <a href="{{ route('register') }}" class="bg-brand-500 hover:bg-brand-600 text-white px-5 py-2.5 rounded-full text-sm font-medium transition shadow-lg shadow-brand-500/30">
                            Join Hiyoucan
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <div class="relative pt-32 pb-20 lg:pt-40 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-8">
                    <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-gray-900 leading-tight">
                        Unlock Your <br>
                        <span class="text-brand-500">True Glow.</span>
                    </h1>
                    <p class="text-lg text-gray-600 max-w-lg">
                        Skincare premium yang diformulasikan untuk kulit Indonesia. Hiyoucan hadir untuk menemani perjalanan glowing-mu setiap hari.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#products" class="bg-gray-900 text-white px-8 py-4 rounded-full font-semibold hover:bg-gray-800 transition shadow-xl">
                            Belanja Sekarang
                        </a>
                        <a href="#" class="flex items-center text-gray-900 font-semibold px-6 py-4 hover:text-brand-500 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Tonton Story
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-brand-200 rounded-full blur-3xl opacity-30"></div>
                    <img src="https://images.unsplash.com/photo-1620916566398-39f1143ab7be?q=80&w=1887&auto=format&fit=crop" 
                         alt="Skincare Hiyoucan" 
                         class="relative rounded-[2.5rem] shadow-2xl rotate-3 hover:rotate-0 transition duration-500 border-4 border-white object-cover h-[500px] w-full">
                </div>
            </div>
        </div>
    </div>

    <div id="products" class="bg-white py-20 rounded-t-[3rem]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-brand-500 font-semibold tracking-wider uppercase text-sm">Pilihan Terbaik</span>
                <h2 class="text-3xl md:text-4xl font-bold mt-2 text-gray-900">Koleksi Hiyoucan</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                
                @php
                    // Simulasi data produk jika belum ada di database
                    $products = \App\Models\Product::with('category')->take(4)->get();
                @endphp

                @forelse($products as $product)
                <div class="group relative bg-white border border-gray-100 rounded-3xl overflow-hidden hover:shadow-2xl hover:shadow-brand-100 transition-all duration-300">
    <div class="aspect-square bg-gray-100 relative overflow-hidden">
        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://images.unsplash.com/photo-1629198688000-71f23e745b6e?q=80&w=1780&auto=format&fit=crop' }}" 
             class="object-cover w-full h-full group-hover:scale-110 transition duration-500">
        
        @if($product->stock < 5 && $product->stock > 0)
            <span class="absolute top-4 left-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">Stok Menipis!</span>
        @elseif($product->stock == 0)
            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                <span class="text-white font-bold text-lg">Habis</span>
            </div>
        @endif
    </div>
    <div class="p-6">
        <div class="text-xs text-gray-500 mb-2">{{ $product->category->name ?? 'Skincare' }}</div>
        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-brand-600 transition truncate">{{ $product->name }}</h3>
        
        <div class="flex justify-between items-end mt-4">
            <span class="text-xl font-bold text-brand-600">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
            
            @if($product->stock > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="bg-gray-900 text-white p-3 rounded-full hover:bg-brand-500 transition shadow-lg tooltip" title="Add to Cart">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                </form>
            @else
                <button disabled class="bg-gray-300 text-white p-3 rounded-full cursor-not-allowed">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            @endif
        </div>
    </div>
</div>
@empty
<div class="col-span-4 text-center py-10">
    <p class="text-gray-500">Belum ada produk skincare. Coba tambahkan lewat Admin Panel!</p>
</div>
@endforelse

            </div>
        </div>
    </div>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <h3 class="text-2xl font-bold mb-4">Hiyoucan.</h3>
            <p class="text-gray-400 mb-8">Glow up your skin, glow up your life.</p>
            <div class="text-sm text-gray-600">
                &copy; {{ date('Y') }} Hiyoucan Skincare. All rights reserved.
            </div>
        </div>
    </footer>

</body>
</html>