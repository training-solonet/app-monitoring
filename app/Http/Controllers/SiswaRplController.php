<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use Carbon\Carbon;

class SiswaRplController extends Controller
{
    public function index()
    {
        $siswarpl = Siswa::all()->map(function ($item) {
            if ($item->waktu_mulai && $item->waktu_selesai) {
                $waktuMulai = Carbon::parse($item->waktu_mulai);
                $waktuSelesai = Carbon::parse($item->waktu_selesai);
                $item->total_waktu = $waktuSelesai->diff($waktuMulai)->format('%H:%I:%S'); // format HH:MM:SS
            } else {
                $item->total_waktu = '-';
            }
            return $item;
        });

        return view('monitoring_siswa.siswarpl', compact('siswarpl'));
    }


  
}
