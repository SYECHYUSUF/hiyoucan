<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Hiyoucan') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['"Plus Jakarta Sans"', 'sans-serif'] },
                    colors: {
                        brand: {
                            50: '#fdf2f8', 100: '#fce7f3', 500: '#ec4899', 600: '#db2777', 900: '#831843',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-pink-50 text-gray-800">
    
    <nav class="bg-white/80 backdrop-blur-md fixed w-full z-50 top-0 border-b border-pink-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="/" class="text-2xl font-bold bg-gradient-to-r from-brand-500 to-purple-500 text-transparent bg-clip-text">
                    Hiyoucan.
                </a>

                <div class="flex items-center gap-6">
                    <a href="{{ route('cart.index') }}" class="text-gray-500 hover:text-brand-600 transition flex items-center gap-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Cart
                    </a>
                    
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-red-500 hover:text-red-700">
                            Log Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-12">
        {{ $slot }}
    </main>

</body>
</html>