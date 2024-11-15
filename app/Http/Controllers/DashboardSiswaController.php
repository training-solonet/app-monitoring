<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\siswa;
use Illuminate\Support\Facades\Auth;

class DashboardSiswaController extends Controller
{
public function index(Request $request)
{
    $statusFilter = $request->get('status', 'all');
    $userId = Auth::id();

    $siswaQuery = Siswa::where('user_id', $userId);

    if ($statusFilter !== 'all') {
        $siswaQuery->where('status', $statusFilter);
    }

    $siswa = $siswaQuery->get()->map(function ($item) {
        if ($item->waktu_mulai && $item->waktu_selesai) {
            $waktuMulai = Carbon::parse($item->waktu_mulai);
            $waktuSelesai = Carbon::parse($item->waktu_selesai);
            $item->total_waktu = $waktuSelesai->diffInMinutes($waktuMulai); // Menghitung total waktu dalam menit
        } else {
            $item->total_waktu = 0;
        }
        return $item;
    });

    // Menghitung total waktu per status
    $totalWaktu = $siswa->groupBy('status')->map(function ($group) {
        return $group->sum('total_waktu');
    });

    // Menambahkan label status ke data chart
    $statusCounts = [
        'Selesai' => $totalWaktu->get('done', 0),
        'Sedang Berlangsung' => $totalWaktu->get('doing', 0),
        'Belum Mulai' => $totalWaktu->get('to do', 0),
    ];

    return view('dashboard_siswa', compact('siswa', 'statusCounts'));
}


}