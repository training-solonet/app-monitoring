<?php

namespace App\Http\Controllers;

// use Barryvdh\DomPDF\PDF;

use Barryvdh\DomPDF\Facade\Pdf;
// use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;


class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $html = '<h1>Dokumentasi Monitoring</h1><p>Isi dari dokumentasi...</p>';

        // Membuat PDF dari HTML        
        // Membuat PDF dari HTML menggunakan app('dompdf.wrapper')

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        // Download file PDF
        return $pdf->download('Dokumentasi_Monitoring.pdf');
    }
}
