<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\materi;

class MateriController extends Controller
{
    public function index(){
        $materi =  Materi::all();
        return view('monitoring_siswa.materi',compact ('materi'));
    }
}
