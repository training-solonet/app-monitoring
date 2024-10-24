<?php

namespace App\Http\Controllers;

use App\Models\SiswaTkj;
use Illuminate\Http\Request;

class SiswaTkjController extends Controller
{
    public function index() {
        $siswa_tkj = SiswaTkj::all();
        return view('siswa_tkj.index', compact('siswa_tkj'));
    }
}
