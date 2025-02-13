<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $filePath = storage_path('app/public/dokumentasi/Dokumentasi Monitoring.pdf');

        // Cek apakah file ada
        if (!file_exists($filePath)) {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }

        // Mengembalikan file untuk di-download
        return response()->download($filePath, 'Dokumentasi Monitoring.pdf');
    }

    public function cekFile()
    {
        $filePath = 'dokumentasi/Dokumentasi Monitoring.pdf';

        if (Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'File ditemukan']);
        } else {
            return response()->json(['error' => 'File tidak ditemukan.'], 404);
        }
    }
}
