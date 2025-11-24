<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    // Tampilkan Form Register
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Register
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'in:buyer,seller'],
        ]);

        // Tentukan Status Seller
        $sellerStatus = null;
        if ($request->role === 'seller') {
            $sellerStatus = 'pending';
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'seller_status' => $sellerStatus,
        ]);

        Auth::login($user);

        // Redirect Seller Baru ke Halaman Pending
        if ($user->role === 'seller') {
            return redirect()->route('seller.pending');
        }

        return redirect()->route('dashboard');
    }

    // Tampilkan Form Login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Proses Login (INI YANG TADI ERROR)
    public function login(Request $request)
    {
        // 1. Validasi Input & Simpan ke variabel $credentials
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba Login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // A. Cek Admin
            if ($user->role === 'admin') {
                return redirect('/admin');
            }

            // B. Cek Seller
            if ($user->role === 'seller') {
                // Jika belum approved, lempar ke halaman pending
                if ($user->seller_status !== 'approved') {
                    return redirect()->route('seller.pending');
                }
                return redirect('/admin'); // Jika approved, masuk Filament
            }

            // C. Buyer
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Proses Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}