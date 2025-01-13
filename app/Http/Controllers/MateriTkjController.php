<?php

namespace App\Http\Controllers;

use App\Models\MateriTkj;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriTkjController extends Controller
{
    public function index()
    {
        // menyimpan koleksi data materi yang memiliki jurusan 'TKJ' yang diambil dari model MateriTkj.
        $materitkj = MateriTkj::where('jurusan', 'TKJ')->get();

        return view('pembimbing.materi', compact('materitkj'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
            'jurusan' => 'required|in:TKJ,RPL',
        ]);

        // menyimpan lokasi penyimpanan file materi yang diunggah oleh pengguna.
        $filePath = null;
        if ($request->hasFile('file_materi')) {
            // menyimpan nama asli dari file yang diunggah.
            $originalFileName = $request->file('file_materi')->getClientOriginalName();
            $filePath = $request->file('file_materi')->storeAs('materi_files', $originalFileName, 'public');
        }

        MateriTkj::create([
            'materi' => $request->materi,
            'detail' => $request->detail,
            'file_materi' => $filePath,
            'jurusan' => $request->jurusan,
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

        $materi = MateriTkj::findOrFail($id);

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
        $materi = MateriTkj::findOrFail($id);
        if ($materi->file_materi) {
            \Storage::disk('public')->delete($materi->file_materi);
        }
        $materi->delete();

        return redirect()->back()->with('success', 'Materi berhasil dihapus');
    }
}
