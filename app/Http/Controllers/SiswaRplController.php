<?php

namespace App\Http\Controllers;

use App\Models\SiswaRpl;
use Illuminate\Http\Request;

class SiswaRplController extends Controller
{
    public function index(){
        $siswa_rpl = SiswaRpl::all();
        return view('siswa_rpl.index', compact('siswa_rpl'));
    }
}
