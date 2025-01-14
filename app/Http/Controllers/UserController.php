<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        // memanggil semua data user
        $users = User::all();

        return view('laravel-examples.users-management', compact('users'));
    }
}
