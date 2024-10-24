<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AdminController extends Controller
{
    public function index(){
        $admin = Admin::all();
        $siswa = Siswa::all();
        $siswa_tkj = SiswaTkj::all();
        $siswa_rpl = SiswaRpl::all();
        $pemimbing = Pembimbing::all();
        return view('admin.index', compact('Admin', 'Siswa', 'SiswaTkj', 'SiswaRpl', 'Pembimbing'));
    }
}
