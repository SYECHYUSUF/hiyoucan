<x-layouts.app>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-pink-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-pink-100 p-8">
                
                <h1 class="text-3xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                    <span class="text-pink-500">🛍️</span> Your Hiyoucan Cart
                </h1>

                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(!$cart || $cart->items->isEmpty())
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">Keranjang kamu masih kosong, bestie.</p>
                        <a href="/" class="mt-4 inline-block bg-pink-500 text-white px-6 py-2 rounded-full hover:bg-pink-600 transition">
                            Mulai Belanja
                        </a>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-gray-500 border-b border-gray-200">
                                    <th class="py-4">Produk</th>
                                    <th class="py-4">Harga</th>
                                    <th class="py-4 text-center">Jumlah</th>
                                    <th class="py-4 text-right">Total</th>
                                    <th class="py-4"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $grandTotal = 0; @endphp
                                @foreach($cart->items as $item)
                                @php 
                                    $subtotal = $item->product->price * $item->quantity;
                                    $grandTotal += $subtotal;
                                @endphp
                                <tr class="border-b border-gray-100 hover:bg-pink-50/30 transition">
                                    <td class="py-4 flex items-center gap-4">
                                        <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/80' }}" 
                                             class="w-16 h-16 rounded-xl object-cover shadow-sm">
                                        <div>
                                            <p class="font-bold text-gray-800">{{ $item->product->name }}</p>
                                            <span class="text-xs text-pink-500 bg-pink-100 px-2 py-1 rounded-full">
                                                {{ $item->product->category->name ?? 'Skincare' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="py-4 text-gray-600">
                                        Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-center font-medium">
                                        {{ $item->quantity }} pcs
                                    </td>
                                    <td class="py-4 text-right font-bold text-gray-800">
                                        Rp {{ number_format($subtotal, 0, ',', '.') }}
                                    </td>
                                    <td class="py-4 text-right">
                                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 font-medium text-sm">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-8 flex justify-end items-center gap-8">
                        <div class="text-right">
                            <p class="text-gray-500">Total Belanja</p>
                            <p class="text-3xl font-bold text-pink-600">Rp {{ number_format($grandTotal, 0, ',', '.') }}</p>
                        </div>
                        <form action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-gray-900 text-white px-8 py-4 rounded-2xl font-bold text-lg hover:bg-gray-800 shadow-xl transition transform hover:-translate-y-1">
                                Checkout Sekarang
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>