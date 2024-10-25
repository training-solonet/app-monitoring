<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class SiswaController extends Controller
{
    public function index() {
        $siswa = Siswa::all();
        return view('monitoring_siswa.siswa', compact('siswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|in:DiKantor,Keluar Dengan Teknisi',
            'report' => 'required'
        ]);

        Siswa::create($request->all());

        return redirect()->route('siswa.index')->with('success', 'BTS berhasil ditambahkan.');
    }
}
