<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembimbing;

class PembimbingController extends Controller
{
    Public Function Index(){
        $pembimbing = Pembimbing::all();
        return view('pembimbing.index', compact('pembimbing'));
    }
}
