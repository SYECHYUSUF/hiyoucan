<x-layouts.app>
    <div class="py-12 bg-pink-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-3xl shadow-xl p-8 border border-pink-100">
            
            <div class="text-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Tulis Ulasan</h2>
                <p class="text-gray-500 text-sm">Bagaimana pengalamanmu menggunakan produk ini?</p>
            </div>

            <div class="flex items-center gap-4 bg-gray-50 p-4 rounded-xl mb-6">
                <img src="{{ $product->image_path ? asset('storage/' . $product->image_path) : 'https://via.placeholder.com/80' }}" 
                     class="w-16 h-16 rounded-lg object-cover">
                <div>
                    <h3 class="font-bold text-gray-800">{{ $product->name }}</h3>
                    <p class="text-xs text-gray-500">Order #{{ $order->order_number }}</p>
                </div>
            </div>

            <form action="{{ route('reviews.store', ['product' => $product->id, 'order' => $order->id]) }}" method="POST">
                @csrf
                
                <div class="mb-6 text-center">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Beri Bintang</label>
                    <div class="flex justify-center flex-row-reverse gap-2 group">
                        @for($i = 5; $i >= 1; $i--)
                            <input type="radio" name="rating" id="star{{$i}}" value="{{$i}}" class="peer hidden" required />
                            <label for="star{{$i}}" class="text-3xl text-gray-300 peer-checked:text-yellow-400 hover:text-yellow-400 peer-hover:text-yellow-400 cursor-pointer transition">★</label>
                        @endfor
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Ceritakan Pengalamanmu</label>
                    <textarea name="comment" rows="4" class="w-full bg-gray-50 border border-gray-200 rounded-xl p-3 focus:outline-none focus:border-pink-500 transition" placeholder="Produknya bagus banget, bikin glowing..."></textarea>
                </div>

                <div class="flex gap-3">
                    <a href="{{ route('dashboard') }}" class="flex-1 bg-gray-200 text-gray-700 py-3 rounded-xl font-bold text-center hover:bg-gray-300 transition">Batal</a>
                    <button type="submit" class="flex-1 bg-gray-900 text-white py-3 rounded-xl font-bold hover:bg-brand-600 transition shadow-lg">Kirim Ulasan</button>
                </div>
            </form>

        </div>
    </div>
</x-layouts.app>