<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $filePath = 'dokumentasi/Dokumentasi Monitoring.pdf';

        if (!Storage::exists('public/' . $filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return Storage::download('public/' . $filePath, 'Dokumentasi Monitoring.pdf');
    }
}
