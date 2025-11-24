<x-layouts.app>
    <div class="py-16 bg-[#FAFAFA] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-8">
                <h1 class="text-3xl font-extrabold text-gray-900">Checkout</h1>
                <p class="text-gray-500">Lengkapi detail pengiriman Anda.</p>
            </div>

            <form action="{{ route('checkout.process') }}" method="POST" x-data="{ addressMode: '{{ $addresses->isNotEmpty() ? 'select' : 'new' }}' }">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                    <div class="lg:col-span-2 space-y-8">

                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                    <span class="bg-primary-50 text-primary-600 p-2 rounded-lg text-lg">📍</span> Alamat
                                    Pengiriman
                                </h2>

                                @if ($addresses->isNotEmpty())
                                    <button type="button" @click="addressMode = 'new'"
                                        x-show="addressMode === 'select'"
                                        class="text-sm font-bold text-primary-600 hover:underline">
                                        + Alamat Baru
                                    </button>
                                    <button type="button" @click="addressMode = 'select'"
                                        x-show="addressMode === 'new'"
                                        class="text-sm font-bold text-gray-500 hover:text-gray-900">
                                        Kembali ke Daftar
                                    </button>
                                @endif
                            </div>

                            <div x-show="addressMode === 'select'" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach ($addresses as $addr)
                                    <label class="cursor-pointer relative">
                                        <input type="radio" name="address_id" value="{{ $addr->id }}"
                                            class="peer sr-only" {{ $loop->first ? 'checked' : '' }}>
                                        <div
                                            class="p-5 rounded-2xl border-2 border-gray-100 hover:border-primary-200 bg-gray-50/50 peer-checked:border-primary-500 peer-checked:bg-primary-50/30 transition h-full">
                                            <div class="flex justify-between mb-2">
                                                <span class="font-bold text-gray-900">{{ $addr->label }}</span>
                                                <div
                                                    class="w-5 h-5 rounded-full border-2 border-gray-300 peer-checked:border-primary-500 peer-checked:bg-primary-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full"></div>
                                                </div>
                                            </div>
                                            <p class="text-sm font-bold text-gray-800">{{ $addr->recipient_name }}</p>
                                            <p class="text-xs text-gray-500">{{ $addr->phone_number }}</p>
                                            <p class="text-sm text-gray-600 mt-2 leading-relaxed">
                                                {{ $addr->full_address }}</p>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                            <div x-show="addressMode === 'new'" x-transition>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Label
                                            Alamat</label>
                                        <input type="text" name="new_label" placeholder="Rumah, Kantor, Kost"
                                            class="w-full rounded-xl border-gray-200 p-3 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama
                                            Penerima</label>
                                        <input type="text" name="new_recipient" placeholder="Nama Lengkap"
                                            class="w-full rounded-xl border-gray-200 p-3 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition">
                                    </div>
                                </div>
                                <div class="mb-5">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor
                                        Telepon</label>
                                    <input type="text" name="new_phone" placeholder="0812..."
                                        class="w-full rounded-xl border-gray-200 p-3 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat
                                        Lengkap</label>
                                    <textarea name="new_address" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota, Kode Pos"
                                        class="w-full rounded-xl border-gray-200 p-3 focus:ring-primary-500 focus:border-primary-500 bg-gray-50 focus:bg-white transition"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-3xl shadow-sm border border-gray-100">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">📦 Isi Paket</h2>
                            <div class="space-y-6">
                                @php $total = 0; @endphp
                                @foreach ($cart->items as $item)
                                    @php $total += $item->product->price * $item->quantity; @endphp
                                    <div class="flex gap-5">
                                        <img src="{{ $item->product->image_path ? asset('storage/' . $item->product->image_path) : 'https://via.placeholder.com/100' }}"
                                            class="w-20 h-20 rounded-xl object-cover bg-gray-50 border border-gray-100">
                                        <div>
                                            <h3 class="font-bold text-gray-900">{{ $item->product->name }}</h3>
                                            <p class="text-sm text-gray-500 mb-1">{{ $item->quantity }} x Rp
                                                {{ number_format($item->product->price, 0, ',', '.') }}</p>
                                            <p class="font-bold text-primary-600">Rp
                                                {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1">
                        <div class="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 sticky top-28">
                            <h2 class="text-lg font-bold text-gray-900 mb-6">Ringkasan Pembayaran</h2>

                            <div class="space-y-3 mb-6 text-sm text-gray-600">
                                <div class="flex justify-between">
                                    <span>Total Harga Barang</span>
                                    <span>Rp {{ number_format($total, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Biaya Pengiriman</span>
                                    <span class="text-green-600 font-bold">Gratis</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Biaya Layanan</span>
                                    <span>Rp 1.000</span>
                                </div>
                            </div>

                            <div
                                class="border-t border-dashed border-gray-200 pt-4 mb-8 flex justify-between items-center">
                                <span class="font-bold text-gray-900 text-lg">Total Tagihan</span>
                                <span class="font-black text-2xl text-primary-600">Rp
                                    {{ number_format($total + 1000, 0, ',', '.') }}</span>
                            </div>

                            <button type="submit"
                                class="w-full bg-neutral-900 text-white py-4 rounded-xl font-bold text-lg hover:bg-primary-600 hover:shadow-xl hover:shadow-primary-500/30 transition transform hover:-translate-y-1">
                                Buat Pesanan
                            </button>

                            <p class="text-xs text-center text-gray-400 mt-4">
                                Dengan melanjutkan, Anda menyetujui Syarat & Ketentuan Hiyoucan.
                            </p>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
