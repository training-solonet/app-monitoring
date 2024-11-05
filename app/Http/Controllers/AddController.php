<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;

class AddController extends Controller
{
    public function index(){
        $user = User::all();
        $jurusan = Jurusan::all();
        return view('admin.add',compact('user','jurusan'));
    }
}