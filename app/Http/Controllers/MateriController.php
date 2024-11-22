<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        // Mendapatkan jurusan user yang sedang login
        $userJurusan = Auth::user()->jurusan;

        // Mengambil materi berdasarkan jurusan user
        $materi = Materi::where('jurusan', $userJurusan)->get();

        return view('monitoring_siswa.materi', compact('materi'));
    }
}
