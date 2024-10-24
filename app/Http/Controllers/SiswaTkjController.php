<?php

namespace App\Http\Controllers;

use App\Models\SiswaTkj;
use Illuminate\Http\Request;

class SiswaTkjController extends Controller
{
    public function index() {
        $siswa_tkj = SiswaTkj::all();
        return view('monitoring_siswa.tb_siswa_tkj', compact('siswa_tkj'));
    }
}
