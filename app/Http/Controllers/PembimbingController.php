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

    public function store(Request $request){
        $request->validate([
            'username' =>  'required|string',
            'password' =>  'required|text',
        ]);
    }

    public function destroy(string $id){
        $pembimbing = pembimbing::find($id);
        $pembimbing ->delete();

        return redirect()->route('pembimbing.index')->with('Succes', 'Dihapus');
    }
}
