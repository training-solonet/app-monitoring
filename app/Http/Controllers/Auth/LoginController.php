<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                Auth::login($user, $request->filled('remember'));

                if ($request->filled('remember')) {
                    $rememberTokenName = Auth::getRecallerName();
                    cookie()->queue(cookie()->make(
                        $rememberTokenName,
                        request()->cookie($rememberTokenName),
                        60 * 24 * 7 // 7 hari
                    ));
                }

                session([
                    'role' => $user->role,
                    'jurusan' => $user->jurusan,
                ]);
                // fd
                if ($user->role === 'siswa') {
                    if ($user->jurusan === 'RPL') {
                        return redirect()->route('dashboardrpl.index');
                    } elseif ($user->jurusan === 'TKJ') {
                        return redirect()->route('dashboardsiswa.index');
                    } elseif ($user->jurusan === 'DKV') {
                        return redirect()->route('dashboardsiswa.index');
                    }
                } elseif ($user->role === 'pembimbing') {
                    return redirect()->route('dashboardpembimbing.index');
                }
            }

            return back()->withErrors(['message' => 'Password salah.'])->withInput($request->only('username'));
        }

        return back()->withErrors(['message' => 'Username tidak terdaftar.']);
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/sign-in');
    }
}
