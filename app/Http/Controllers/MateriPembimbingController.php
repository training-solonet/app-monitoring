<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriPembimbing;
class MateriPembimbingController extends Controller
{
    public function index(){
        $materipembimbing = MateriPembimbing::all();
        return view('pembimbing.materi',compact('materipembimbing'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
        ]);

        $filePath = null;
        if ($request->hasFile('file_materi')) {
            $originalFileName = $request->file('file_materi')->getClientOriginalName();
            $filePath = $request->file('file_materi')->storeAs('materi_files', $originalFileName, 'public');
        }

        MateriPembimbing::create([
            'materi' => $request->materi,
            'detail' => $request->detail,
            'file_materi' => $filePath,
        ]);

        return redirect()->back()->with('success', 'Materi berhasil upload');
    }  

}
