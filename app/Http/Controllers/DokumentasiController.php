<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Barryvdh\DomPDF\PDF;

use Barryvdh\DomPDF\Facade\Pdf;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $html = '<h1>Dokumentasi Monitoring</h1><p>Isi dari dokumentasi...</p>';
        
        // Membuat PDF dari HTML
        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);
        
        // Download file PDF

        $pdf = Pdf::loadHTML($html);

        return $pdf->download('Dokumentasi_Monitoring.pdf');
    }
}
