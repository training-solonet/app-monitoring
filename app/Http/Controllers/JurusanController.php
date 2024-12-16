<?php

namespace App\Http\Controllers;

use App\Models\Jurusan;
use Illuminate\Http\Request;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusan = Jurusan::all();

        return view('admin.jurusan', compact('jurusan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jurusan' => 'required|max:255|unique:jurusan',
        ], [
            'jurusan.required' => 'jurusan is required',
        ]);

        $jurusan = Jurusan::create([
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('jurusan.index')->with('success', 'jurusan berhasil ditambahkan.');
    }
}
