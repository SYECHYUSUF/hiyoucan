<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Akun Seller - Hiyoucan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-6">
    <div class="w-full max-w-lg bg-white p-8 rounded-3xl shadow-xl text-center">
        
        @if($user->seller_status === 'pending')
            <div class="mb-6 bg-yellow-100 text-yellow-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto text-4xl">
                ⏳
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Akun Sedang Ditinjau</h1>
            <p class="text-gray-500 mb-6">
                Terima kasih telah mendaftar sebagai Seller di Hiyoucan. <br>
                Admin kami sedang memverifikasi data Anda. Mohon tunggu persetujuan sebelum dapat mengakses dashboard toko.
            </p>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-brand-600 font-bold hover:underline">Logout dulu</button>
            </form>

        @elseif($user->seller_status === 'rejected')
            <div class="mb-6 bg-red-100 text-red-600 w-20 h-20 rounded-full flex items-center justify-center mx-auto text-4xl">
                ❌
            </div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">Pendaftaran Ditolak</h1>
            <p class="text-gray-500 mb-6">
                Maaf, permohonan Anda untuk menjadi Seller tidak disetujui oleh Admin. <br>
                Anda dapat menghapus akun ini dan mencoba mendaftar kembali.
            </p>
            
            <form action="{{ route('seller.delete') }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-full font-bold hover:bg-red-600 transition shadow-lg w-full mb-4">
                    Delete Account
                </button>
            </form>
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-400 font-bold hover:text-gray-600">Logout</button>
            </form>
        @endif

    </div>
</body>
</html>