<?php

namespace App\Http\Controllers\Auth;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    $request->validate([
        'username' => 'required|max:255|unique:users',
        'password' => 'required|min:7|max:255',
    ], [
        'username.required' => 'Username is required',
        'password.required' => 'Password is required',
    ]);

    $user = User::create([
        'username' => $request->username,
        'password' => $request->password, 
    ]);

    return redirect()->route('add.index')->with('success', 'User berhasil ditambahkan.');
}

}