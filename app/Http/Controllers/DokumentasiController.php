<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;

class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $html = '<h1>Dokumentasi Monitoring</h1><p>Isi dari dokumentasi...</p>';

        $pdf = app('dompdf.wrapper');
        $pdf->loadHTML($html);

        // Download file PDF
        return $pdf->download('Dokumentasi_Monitoring.pdf');
    }
}
