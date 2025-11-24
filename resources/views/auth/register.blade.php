<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Hiyoucan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-pink-50 flex items-center justify-center min-h-screen py-10">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text">Hiyoucan.</h1>
            <p class="text-gray-500 text-sm mt-2">Join us and start your journey! 💖</p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Full Name</label>
                <input type="text" name="name" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="Your Name" required>
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="hello@hiyoucan.com" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="••••••••" required>
                @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="••••••••" required>
            </div>
            
            <div class="mb-6">
    <label class="block text-gray-700 text-sm font-bold mb-2">Daftar Sebagai</label>
    <div class="flex gap-4">
        <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-4 py-3 rounded-xl border border-gray-200 w-1/2 has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
            <input type="radio" name="role" value="buyer" class="peer accent-pink-500" checked>
            <span class="text-gray-700 font-medium">Buyer (Pembeli)</span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer bg-gray-50 px-4 py-3 rounded-xl border border-gray-200 w-1/2 has-[:checked]:border-pink-500 has-[:checked]:bg-pink-50">
            <input type="radio" name="role" value="seller" class="peer accent-pink-500">
            <span class="text-gray-700 font-medium">Seller (Penjual)</span>
        </label>
    </div>
</div>

<button type="submit" class="w-full bg-pink-500 text-white font-bold py-3 rounded-xl hover:bg-pink-600 transition shadow-lg shadow-pink-500/30">
    Register Now
</button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-pink-600 font-bold hover:underline">Login disini</a>
        </p>
    </div>
</body>
</html>