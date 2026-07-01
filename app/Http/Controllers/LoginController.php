<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function index()
    {
        return view('login');
    }

    // Menampilkan halaman register (Tambahkan ini untuk memperbaiki error)
    public function showRegister()
    {
        return view('register');
    }

    // Memproses data login
    public function login(Request $request)
    {
        $data = $request->only('email', 'password');

        if (Auth::attempt($data)) {
            return redirect('/admin/dashboard');
        }

        return back()->with('error', 'Login gagal');

    }

    // Memproses data registrasi (Opsional: siapkan metodenya)
    public function register(Request $request)
    {
        // Logika untuk menyimpan user baru akan di sini
    }

    // Proses logout
    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}