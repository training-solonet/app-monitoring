<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;

class LoginController extends Controller
{
    public function create()
    {
        return view('auth.signin');
    }

    public function store(Request $request)
{
    $request->validate([
        'username' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('username', $request->username)->first();

    if ($user) {
        if ($user->status === 'Tidak Aktif') {
            return back()->withErrors(['message' => 'Akun Anda Sudah Tidak Aktif.']);
        }

        if ($user->password === $request->password) {
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }
    }

    return back()->withErrors(['message' => 'Username atau password salah.'])->withInput($request->only('username'));
}

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/sign-in');
    }
}
