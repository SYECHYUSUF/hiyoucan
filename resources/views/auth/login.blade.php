<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Hiyoucan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-pink-50 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white p-8 rounded-3xl shadow-xl border border-pink-100">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-pink-500 to-purple-500 text-transparent bg-clip-text">Hiyoucan.</h1>
            <p class="text-gray-500 text-sm mt-2">Welcome back, Glowers! ✨</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email Address</label>
                <input type="email" name="email" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="hello@hiyoucan.com" required>
                @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition" placeholder="••••••••" required>
            </div>

            <button type="submit" class="w-full bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-pink-600 transition shadow-lg shadow-pink-500/20">
                Log in
            </button>
        </form>

        <p class="text-center text-gray-500 text-sm mt-6">
            Belum punya akun? <a href="{{ route('register') }}" class="text-pink-600 font-bold hover:underline">Daftar disini</a>
        </p>
    </div>
</body>
</html>