<x-layouts.app>
    <div class="bg-pink-50 min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <h1 class="text-3xl font-bold text-gray-800">✨ Shop Skincare</h1>

                <form method="GET" action="{{ route('shop.index') }}" class="flex flex-wrap gap-2">
                    <select name="category" class="rounded-lg border-gray-200 text-sm focus:ring-pink-500">
                        <option value="">All Categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->slug }}"
                                {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari produk..."
                        class="rounded-lg border-gray-200 text-sm focus:ring-pink-500">
                    <button type="submit"
                        class="bg-pink-500 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-pink-600">Filter</button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($products as $product)
                    <div
                        class="group bg-white rounded-3xl shadow-sm hover:shadow-xl transition overflow-hidden border border-pink-100 flex flex-col">
                        <a href="{{ route('shop.show', $product->slug) }}"
                            class="block aspect-square bg-gray-100 relative overflow-hidden">
                            <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/300' }}"
                                class="object-cover w-full h-full group-hover:scale-110 transition duration-500">
                        </a>

                        <div class="p-5 flex flex-col flex-grow">
                            <div class="text-xs text-gray-500 mb-1">{{ $product->category->name ?? 'General' }}</div>
                            <a href="{{ route('shop.show', $product->slug) }}">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 hover:text-pink-600 transition">
                                    {{ $product->name }}</h3>
                            </a>

                            <div class="mt-auto flex justify-between items-center">
                                <span class="text-xl font-bold text-pink-600">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</span>

                                <div class="flex gap-2">
                                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="p-2 rounded-full hover:bg-pink-50 text-pink-400 hover:text-pink-600 transition">
                                            ♥
                                        </button>
                                    </form>
                                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                        @csrf
                                        <button
                                            class="bg-gray-900 text-white p-2 rounded-lg hover:bg-pink-500 transition">
                                            + Cart
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-4 text-center text-gray-500 py-12">Produk tidak ditemukan.</div>
                @endforelse
            </div>

            <div class="mt-10">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</x-layouts.app>
