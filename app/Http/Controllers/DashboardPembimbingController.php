<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Materi;

class DashboardPembimbingController extends Controller
{
    public function index()
    {
        // Mengambil jumlah data berdasarkan jurusan
        $rplCount = Materi::where('jurusan', 'RPL')->count();
        $tkjCount = Materi::where('jurusan', 'TKJ')->count();

        // Data untuk chart Pie dan Bar
        $chartData = [
            'labels' => ['RPL', 'TKJ'],
            'datasets' => [
                [
                    'data' => [$rplCount, $tkjCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                    'hoverOffset' => 15,
                ]
            ]
        ];

        // Data jumlah aktivitas kategori
        $activityData = [
            'Learning' => rand(10, 20), // Contoh data, sesuaikan dengan logika
            'Project' => rand(15, 25),
            'Di Kantor' => rand(8, 15),
            'Keluar Dengan Teknisi' => rand(5, 12),
        ];

        return view('dashboard', compact('chartData', 'activityData'));
    }
}
