<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $filePath = 'dokumentasi/Dokumentasi Monitoring.pdf';

        // Perbaikan: Gunakan Storage::disk('public')
        if (! Storage::disk('public')->exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }

        return response()->download(storage_path("app/public/$filePath"));
    }
}
