<x-layouts.app>
    <div class="relative bg-white overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-r from-brand-50/80 to-purple-50/50"></div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24 md:py-32 flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 z-10">
                <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-600 text-xs font-bold uppercase tracking-wider mb-4">New Arrival 2025</span>
                <h1 class="text-5xl md:text-7xl font-black text-gray-900 leading-tight mb-6">
                    Glow Up <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-500 to-purple-600">Your Real Skin.</span>
                </h1>
                <p class="text-lg text-gray-500 mb-8 max-w-lg leading-relaxed">
                    Temukan perawatan kulit terbaik yang diformulasikan khusus untuk kulit tropis Indonesia. Asli, BPOM, dan Terpercaya.
                </p>
                <div class="flex gap-4">
                    <a href="{{ route('shop.index') }}" class="bg-gray-900 text-white px-8 py-4 rounded-full font-bold hover:bg-brand-600 hover:shadow-lg hover:shadow-brand-500/40 transition transform hover:-translate-y-1">
                        Belanja Sekarang
                    </a>
                </div>
            </div>
            <div class="md:w-1/2 mt-12 md:mt-0 relative">
                <div class="absolute top-10 right-10 w-72 h-72 bg-brand-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
                <div class="absolute bottom-10 left-10 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
                <img src="https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?q=80&w=1000&auto=format&fit=crop" class="relative rounded-[3rem] shadow-2xl border-4 border-white rotate-2 hover:rotate-0 transition duration-700 object-cover h-[500px] w-full">
            </div>
        </div>
    </div>

    <div class="py-24 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Pilihan Editor</h2>
                    <p class="text-gray-500 mt-2">Produk yang paling banyak dicari minggu ini.</p>
                </div>
                <a href="{{ route('shop.index') }}" class="text-brand-600 font-bold hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($products as $product)
                <div class="group bg-white rounded-3xl p-3 border border-gray-100 hover:border-brand-200 hover:shadow-xl hover:shadow-brand-100/50 transition duration-300 flex flex-col relative">
                    
                    <button onclick="toggleWishlist({{ $product->id }})" class="absolute top-5 right-5 z-20 bg-white/80 backdrop-blur p-2 rounded-full text-gray-400 hover:text-red-500 hover:bg-red-50 transition shadow-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                    </button>

                    <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-100 relative mb-4">
                        <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/400' }}" 
                             class="object-cover w-full h-full group-hover:scale-110 transition duration-700">
                        
                        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition duration-300">
                            <form action="{{ route('cart.buyNow', $product->id) }}" method="POST">
                                @csrf
                                <button class="w-full bg-white/90 backdrop-blur text-gray-900 font-bold py-3 rounded-xl hover:bg-brand-500 hover:text-white transition shadow-lg">
                                    Beli Langsung
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="px-2 pb-2 flex flex-col flex-grow">
                        <div class="text-xs text-gray-400 mb-1">{{ $product->category->name ?? 'Skincare' }}</div>
                        <a href="{{ route('shop.show', $product->slug) }}" class="text-lg font-bold text-gray-900 mb-1 line-clamp-1 hover:text-brand-600 transition">
                            {{ $product->name }}
                        </a>
                        <div class="mt-auto pt-3 flex justify-between items-center border-t border-gray-50">
                            <span class="text-xl font-black text-gray-900">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                            <button onclick="addToCart({{ $product->id }})" class="bg-gray-100 text-gray-900 p-2.5 rounded-xl hover:bg-gray-900 hover:text-white transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-20">
                    <p class="text-gray-400">Belum ada produk nih.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Wishlist AJAX
        function toggleWishlist(id) {
            fetch(`/wishlist/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => {
                if(res.status === 401) window.location.href = '/login';
                return res.json();
            })
            .then(data => {
                document.querySelector('[x-data]').__x.$data.add(data.message, 'success');
            });
        }

        // Add to Cart AJAX
        function addToCart(id) {
            fetch(`/cart/add/${id}`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' }
            })
            .then(res => {
                if(res.status === 401) window.location.href = '/login';
                return res.json();
            })
            .then(data => {
                if(data.status === 'success') {
                    document.querySelector('[x-data]').__x.$data.add(data.message, 'success');
                } else {
                    document.querySelector('[x-data]').__x.$data.add(data.message, 'error');
                }
            });
        }
    </script>
</x-layouts.app>