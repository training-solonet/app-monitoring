<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class DokumentasiController extends Controller
{
    public function downloadPDF()
    {
        $html = '<h1>Dokumentasi Monitoring</h1><p>Isi dari dokumentasi...</p>';

        $pdf = Pdf::loadHTML($html);
        return $pdf->download('Dokumentasi_Monitoring.pdf');
    }
}
