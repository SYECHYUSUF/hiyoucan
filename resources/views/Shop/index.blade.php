<x-layouts.app>
    <div class="bg-[#FAFAFA] min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="text-center max-w-2xl mx-auto mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 mb-4">Koleksi Skincare</h1>
                <p class="text-gray-500 text-lg">Temukan produk terbaik untuk kebutuhan kulitmu.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach ($products as $product)
                    <div class="group bg-white rounded-3xl p-3 hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition duration-300 border border-gray-100 relative flex flex-col"
                        x-data="{ inWishlist: {{ Auth::check() && Auth::user()->wishlists->contains('product_id', $product->id) ? 'true' : 'false' }} }">

                        <button
                            @click="
                        fetch('{{ route('wishlist.toggle', $product->id) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
                        })
                        .then(res => {
                            if(res.status === 401) window.location.href = '/login';
                            return res.json();
                        })
                        .then(data => {
                            inWishlist = !inWishlist; // Toggle state visual
                            document.querySelector('[x-data]').__x.$data.add(data.message, 'success'); // Panggil Toast Global
                        })"
                            class="absolute top-5 right-5 z-20 p-2.5 rounded-full backdrop-blur-sm transition shadow-sm"
                            :class="inWishlist ? 'bg-primary-50 text-primary-500' :
                                'bg-white/80 text-gray-400 hover:bg-gray-100'">

                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                            </svg>
                        </button>

                        <a href="{{ route('shop.show', $product->slug) }}"
                            class="block aspect-[4/5] bg-gray-50 rounded-2xl overflow-hidden relative mb-4">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/400' }}"
                                class="object-cover w-full h-full group-hover:scale-105 transition duration-700">
                        </a>

                        <div class="px-2 pb-2 flex flex-col flex-grow">
                            <div class="text-xs font-semibold text-gray-400 mb-1 uppercase tracking-wide">
                                {{ $product->category->name ?? 'Skincare' }}</div>
                            <a href="{{ route('shop.show', $product->slug) }}"
                                class="text-lg font-bold text-gray-900 mb-1 line-clamp-1 hover:text-primary-600 transition">
                                {{ $product->name }}
                            </a>

                            <div class="mt-auto pt-4 flex justify-between items-end">
                                <span class="text-xl font-black text-gray-900">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>

                                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                    @csrf
                                    <button
                                        class="bg-gray-100 text-gray-900 p-3 rounded-xl hover:bg-gray-900 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-layouts.app>
