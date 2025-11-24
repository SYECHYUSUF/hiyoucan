<x-layouts.app>
    <div class="py-16 bg-[#FAFAFA] min-h-screen">
        <div class="max-w-4xl mx-auto px-4">
            
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    
                    <div class="bg-gray-50/50 p-8 border-r border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900 mb-6">Settings</h2>
                        <nav class="space-y-2">
                            <a href="#" class="block px-4 py-3 rounded-xl bg-white shadow-sm text-primary-600 font-bold border border-gray-100">Edit Profil</a>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-xl text-gray-500 hover:bg-gray-100 font-medium transition">Riwayat Pesanan</a>
                        </nav>
                    </div>

                    <div class="col-span-2 p-8 md:p-12">
                        <h3 class="text-2xl font-bold text-gray-900 mb-8">Profil Saya</h3>

                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="flex items-center gap-6 mb-8">
                                <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name).'&background=ffe4e6&color=e11d48' }}" 
                                     class="w-24 h-24 rounded-full object-cover border-4 border-gray-50 shadow-sm">
                                <div>
                                    <label class="block bg-white border border-gray-200 text-gray-700 font-bold py-2 px-4 rounded-xl cursor-pointer hover:bg-gray-50 transition text-sm shadow-sm">
                                        Ganti Foto
                                        <input type="file" name="avatar" class="hidden" onchange="form.submit()">
                                    </label>
                                    <p class="text-xs text-gray-400 mt-2">Max 2MB. JPG, PNG.</p>
                                </div>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-primary-500 focus:border-primary-500 p-3 transition">
                                </div>
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-primary-500 focus:border-primary-500 p-3 transition">
                                </div>
                                
                                <div class="pt-4 border-t border-gray-100">
                                    <p class="text-sm font-bold text-gray-900 mb-4">Ganti Password (Opsional)</p>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <input type="password" name="password" placeholder="Password Baru" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-primary-500 p-3 transition">
                                        <input type="password" name="password_confirmation" placeholder="Ulangi Password" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:ring-primary-500 p-3 transition">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-8 flex justify-end">
                                <button type="submit" class="bg-primary-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-primary-500/30 hover:bg-primary-700 transition transform hover:-translate-y-1">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>