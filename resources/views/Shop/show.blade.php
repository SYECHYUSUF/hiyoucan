<x-layouts.app>
    <div class="py-12 bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-16">
                <div class="rounded-3xl overflow-hidden bg-gray-100 aspect-square shadow-lg">
                    <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/500' }}"
                        class="object-cover w-full h-full">
                </div>

                <div class="flex flex-col justify-center">
                    <div class="mb-4">
                        <span
                            class="bg-pink-100 text-pink-600 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide">
                            {{ $product->category->name ?? 'Skincare' }}
                        </span>
                    </div>
                    <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                    <p class="text-2xl font-bold text-pink-600 mb-6">Rp
                        {{ number_format($product->price, 0, ',', '.') }}</p>

                    <div class="flex items-center gap-3 mb-6 border-b border-gray-100 pb-6">
                        <div class="w-10 h-10 bg-gray-200 rounded-full overflow-hidden">
                            <img src="{{ $product->store->logo_path ? asset('storage/' . $product->store->logo_path) : 'https://via.placeholder.com/40' }}"
                                class="w-full h-full object-cover">
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Dijual oleh</p>
                            <p class="font-bold text-gray-800">{{ $product->store->name ?? 'Official Store' }}</p>
                        </div>
                    </div>

                    <div class="prose text-gray-600 mb-8">
                        {!! $product->description !!}
                    </div>

                    <div class="flex gap-4">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button
                                class="w-full bg-gray-900 text-white py-4 rounded-xl font-bold hover:bg-pink-600 transition shadow-xl">
                                Add to Cart
                            </button>
                        </form>
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button
                                class="bg-white border-2 border-gray-200 text-gray-400 p-4 rounded-xl hover:border-pink-500 hover:text-pink-500 transition">
                                ♥ Wishlist
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-3xl p-8 md:p-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-8">Ulasan Pembeli ({{ $product->reviews->count() }})</h2>

                <div class="space-y-6">
                    @forelse($product->reviews as $review)
                        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-gray-800">{{ $review->user->name }}</div>
                                <div class="text-yellow-400 tracking-widest">
                                    {{ str_repeat('★', $review->rating) }}<span
                                        class="text-gray-200">{{ str_repeat('★', 5 - $review->rating) }}</span>
                                </div>
                            </div>
                            <p class="text-gray-600">{{ $review->comment }}</p>
                            <p class="text-xs text-gray-400 mt-2">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    @empty
                        <p class="text-gray-500 italic">Belum ada ulasan untuk produk ini.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-layouts.app>
