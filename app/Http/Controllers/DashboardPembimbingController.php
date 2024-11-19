<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;


class DashboardPembimbingController extends Controller
{
    
    public function index()
    {
        return view('dashboard');
    }
}
