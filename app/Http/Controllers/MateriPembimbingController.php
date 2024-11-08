<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MateriPembimbing;
use Illuminate\Support\Facades\Storage;
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

    public function update(Request $request, $id)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
        ]);

        $materi = MateriPembimbing::findOrFail($id);

        if ($request->hasFile('file_materi')) {
            if ($materi->file_materi) {
                Storage::disk('public')->delete($materi->file_materi);
            }
            
            $originalFileName = $request->file('file_materi')->getClientOriginalName();
            $filePath = $request->file('file_materi')->storeAs('materi_files', $originalFileName, 'public');
            $materi->file_materi = $filePath;
        }

        $materi->materi = $request->materi;
        $materi->detail = $request->detail;
        $materi->save();

        return redirect()->back()->with('success', 'Materi berhasil diperbarui');
    }

    public function destroy($id)
    {
        $materi = MateriPembimbing::findOrFail($id);
        if ($materi->file_materi) {
            \Storage::disk('public')->delete($materi->file_materi);
        }
        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus');
    }
}
