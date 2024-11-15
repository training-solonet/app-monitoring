<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;


class DashboardPembimbingController extends Controller
{
    public function index()
    {
        // Menghitung jumlah siswa per jurusan
        $jumlahTkj = Siswa::where('jurusan', 'TKJ')->count();
        $jumlahRpl = Siswa::where('jurusan', 'RPL')->count();

        return view('dashboard.pembimbing', compact('jumlahTkj', 'jumlahRpl'));
    }
}
