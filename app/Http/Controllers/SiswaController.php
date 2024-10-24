<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index() {
        $siswa = Siswa::all();
        return view('siswa.index', compact('siswa'));
    }

    public function store(Request $request){
        $request->validate ([
            'username' => 'required|string',
            'password' => 'required|text',
            'jurusan'  => 'required|in:tkj,rpl',
        ]);
    }
}
