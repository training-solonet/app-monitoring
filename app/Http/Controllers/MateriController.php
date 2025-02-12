<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use Illuminate\Support\Facades\Auth;

class MateriController extends Controller
{
    public function index()
    {
        // Mendapatkan jurusan user yang sedang login
        $userJurusan = Auth::user()->jurusan;

        // Mengambil materi berdasarkan jurusan user
        $materi = Materi::where('jurusan', $userJurusan)->paginate(10);

        return view('monitoring_siswa.materi', compact('materi'));
    }
}
