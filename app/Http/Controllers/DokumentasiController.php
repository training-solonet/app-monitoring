<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $filePath = 'dokumentasi/Dokumentasi Monitoring.pdf';

        // Cek apakah file ada
        if (! Storage::disk('public')->exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::disk('public')->download($filePath, 'Dokumentasi Monitoring.pdf');
    }
}
