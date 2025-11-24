<x-layouts.app>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl p-8 mb-8 border border-pink-100 flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-800">Hi, {{ Auth::user()->name }}! ✨</h3>
                <p class="text-gray-500 mt-1">Siap tampil glowing hari ini?</p>
            </div>
            <div class="bg-pink-100 text-pink-600 px-6 py-2 rounded-full font-bold">
                Member Hiyoucan
            </div>
        </div>

        <h3 class="text-xl font-bold text-gray-800 mb-6 ml-2 flex items-center gap-2">
            <span>📦</span> Riwayat Pesanan Kamu
        </h3>
        
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100 min-h-[300px]">
            @if($orders->isEmpty())
                <div class="flex flex-col items-center justify-center h-full py-16">
                    <div class="text-6xl mb-4">🛍️</div>
                    <p class="text-gray-500 text-lg font-medium">Belum ada pesanan nih.</p>
                    <a href="/" class="mt-4 bg-brand-500 text-white px-6 py-3 rounded-full font-bold hover:bg-brand-600 transition shadow-lg shadow-brand-500/30">
                        Mulai Belanja Sekarang
                    </a>
                </div>
            @else
                <div class="divide-y divide-gray-100">
                    @foreach($orders as $order)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex flex-col md:flex-row justify-between items-start mb-4 gap-4">
                            <div>
                                <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">No. Order</span>
                                <p class="font-bold text-gray-800 text-lg font-mono">{{ $order->order_number }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d M Y, H:i') }}</p>
                            </div>
                            <div class="text-left md:text-right">
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide
                                    {{ $order->status === 'completed' ? 'bg-green-100 text-green-600' : 
                                      ($order->status === 'cancelled' ? 'bg-red-100 text-red-600' : 
                                      ($order->status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-blue-100 text-blue-600')) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                                <p class="mt-2 font-bold text-brand-600 text-xl">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>

                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            @foreach($order->items as $item)
                            <div class="flex items-center gap-4 mb-3 last:mb-0">
                                <div class="w-12 h-12 bg-white rounded-lg border border-gray-200 overflow-hidden flex-shrink-0">
                                    <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/50' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-sm font-bold text-gray-800">{{ $item->product->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                </div>

                                @if($order->status === 'completed')
                                    @php
                                        // Cek apakah user sudah mereview produk ini di order ini
                                        $alreadyReviewed = \App\Models\Review::where('user_id', Auth::id())
                                            ->where('product_id', $item->product_id)
                                            ->where('order_id', $order->id)
                                            ->exists();
                                    @endphp

                                    @if(!$alreadyReviewed)
                                        <a href="{{ route('reviews.create', ['product' => $item->product_id, 'order' => $order->id]) }}" 
                                           class="bg-pink-100 text-pink-600 text-xs font-bold px-3 py-1.5 rounded-full hover:bg-pink-500 hover:text-white transition">
                                            ★ Beri Ulasan
                                        </a>
                                    @else
                                        <span class="text-xs text-green-500 font-bold px-3 py-1.5 border border-green-200 rounded-full bg-green-50">
                                            ✓ Ulasan Terkirim
                                        </span>
                                    @endif
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        
    </div>
</x-layouts.app>