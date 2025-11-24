<x-layouts.app>
    <div class="py-12 bg-pink-50 min-h-screen flex justify-center">
        <div class="w-full max-w-2xl bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
            <div class="flex items-center gap-4 mb-6 border-b border-gray-100 pb-4">
                <div class="bg-pink-100 p-3 rounded-full text-2xl">✏️</div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Edit Profil</h2>
                    <p class="text-gray-500 text-sm">Perbarui informasi akunmu</p>
                </div>
            </div>

            @if(session('success'))
                <div class="bg-green-100 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-2">
                    <span>✅</span> {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                        class="w-full rounded-xl border-gray-300 focus:border-pink-500 focus:ring-pink-500 p-3" required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                        class="w-full rounded-xl border-gray-300 focus:border-pink-500 focus:ring-pink-500 p-3" required>
                </div>

                <hr class="my-6 border-gray-100">
                <p class="text-sm text-gray-500 mb-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                    💡 Biarkan kosong jika tidak ingin mengganti password.
                </p>

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2">Password Baru</label>
                    <input type="password" name="password" 
                        class="w-full rounded-xl border-gray-300 focus:border-pink-500 focus:ring-pink-500 p-3">
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" 
                        class="w-full rounded-xl border-gray-300 focus:border-pink-500 focus:ring-pink-500 p-3">
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="bg-gray-100 text-gray-600 px-6 py-3 rounded-xl font-bold hover:bg-gray-200 transition">
                        Batal
                    </a>
                    <button type="submit" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-brand-600 transition shadow-lg">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>