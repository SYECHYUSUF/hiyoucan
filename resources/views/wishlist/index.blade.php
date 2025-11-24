<x-layouts.app>
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-8">♥ Wishlist Saya</h1>

            @if($wishlists->isEmpty())
                <div class="text-center py-12 bg-gray-50 rounded-3xl">
                    <p class="text-gray-500">Belum ada barang impian.</p>
                    <a href="{{ route('shop.index') }}" class="text-pink-600 font-bold hover:underline">Cari produk</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($wishlists as $item)
                        <div class="border border-gray-200 rounded-2xl p-4 relative">
                            <a href="{{ route('shop.show', $item->product->slug) }}">
                                <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/200' }}" class="rounded-xl mb-4 aspect-square object-cover w-full">
                                <h3 class="font-bold text-gray-800 mb-1">{{ $item->product->name }}</h3>
                                <p class="text-pink-600 font-bold">Rp {{ number_format($item->product->price, 0, ',', '.') }}</p>
                            </a>
                            
                            <div class="flex gap-2 mt-4">
                                <form action="{{ route('cart.add', $item->product->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button class="w-full bg-gray-900 text-white py-2 rounded-lg text-sm font-bold">Add Cart</button>
                                </form>
                                <form action="{{ route('wishlist.toggle', $item->product->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-red-100 text-red-500 p-2 rounded-lg hover:bg-red-200">🗑</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>