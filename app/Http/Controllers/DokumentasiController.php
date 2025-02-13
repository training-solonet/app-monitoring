<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function cekFile()
    {
        $filePath = 'dokumentasi/Dokumentasi Monitoring.pdf';

        // Gunakan Storage::disk('public') untuk mengecek keberadaan file
        if (Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'File ditemukan']);
        } else {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }
    }
}
