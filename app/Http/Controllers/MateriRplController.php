<?php

namespace App\Http\Controllers;

use App\Models\MateriTkj;
use App\Models\Materi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriRplController extends Controller
{
    public function index()
{
    // Mengambil data materi yang memiliki jurusan 'RPL' dengan pagination
    $materirpl = Materi::where('jurusan', 'RPL')->paginate(5);

    return view('pembimbing.materirpl', compact('materirpl'));
}

    public function store(Request $request)
    {
        $request->validate([
            'materi' => 'required|string|max:255',
            'detail' => 'nullable|string',
            'file_materi' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx,ppt,pptx,txt|max:5120',
            'jurusan' => 'required|in:TKJ,RPL',
        ]);

        // Menyimpan lokasi path file materi yang diunggah.
        $filePath = null;
        if ($request->hasFile('file_materi')) {
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

        //  Menyimpan objek materi yang diambil dari model MateriTkj berdasarkan ID yang diberikan.
        $materi = MateriTkj::findOrFail($id);

        if ($request->hasFile('file_materi')) {
            if ($materi->file_materi) {
                Storage::disk('public')->delete($materi->file_materi);
            }

            // Menyimpan nama asli file yang diunggah oleh pengguna.
            $originalFileName = $request->file('file_materi')->getClientOriginalName();
            // Menyimpan path file materi yang baru, jika file baru diunggah pada saat pembaruan materi.
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
