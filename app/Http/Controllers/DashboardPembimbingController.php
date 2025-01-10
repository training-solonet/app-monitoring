<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardPembimbingController extends Controller
{
    public function index(Request $request)
    {
        $userList = User::where('role', 'siswa')->get();

        $rplCount = Materi::where('jurusan', 'RPL')->count();
        $tkjCount = Materi::where('jurusan', 'TKJ')->count();

        $chartData = [
            'labels' => ['RPL', 'TKJ'],
            'datasets' => [
                [
                    'data' => [$rplCount, $tkjCount],
                    'backgroundColor' => ['#36A2EB', '#FF6384'],
                    'hoverOffset' => 15,
                ],
            ],
        ];

        $activityData = Siswa::select('kategori')
            ->selectRaw('COUNT(*) as count')
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->groupBy('kategori')
            ->pluck('count', 'kategori');

        // dd($activityData);

        $totalWaktuPerKategori = Siswa::select('kategori')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_selesai) / 3600) as total_waktu') // Convert to hours
            ->groupBy('kategori')
            ->pluck('total_waktu', 'kategori');

        $jumlahDataRPL = Siswa::whereIn('kategori', ['Belajar', 'Projek'])
            ->count();

        $jumlahDataTKJ = Siswa::whereIn('kategori', ['Dikantor', 'Keluar Dengan Teknisi'])
            ->count();

        $totalWaktu = Siswa::get()
            ->reduce(function ($carry, $item) {
                if ($item->waktu_mulai && $item->waktu_selesai) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    if ($waktuSelesai->greaterThan($waktuMulai)) {
                        $carry += $waktuSelesai->diffInSeconds($waktuMulai);
                    }
                }

                return $carry;
            }, 0);

        $totalWaktuPerKategori = Siswa::select('kategori')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_selesai)) as total_waktu')
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->groupBy('kategori')
            ->pluck('total_waktu', 'kategori');


        $totalWaktuSemuaKategori = $totalWaktuPerKategori->sum();

        $persentaseWaktuPerKategori = $totalWaktuPerKategori->map(function ($waktu) use ($totalWaktuSemuaKategori) {
            return $totalWaktuSemuaKategori > 0 ? ($waktu / $totalWaktuSemuaKategori) * 100 : 0;
        });

        // $kategori = ['Belajar', 'Projek', 'DiKantor', 'Keluar dengan Teknisi'];

        // $kantor = DB::table('siswa')
        //     ->join('users', 'siswa.user_id', '=', 'users.id')
        //     ->select('users.username', 'siswa.user_id', DB::raw('COUNT(siswa.kategori) as total_kategori'))
        //     ->whereIn('siswa.kategori', $kategori)
        //     // ->where('user_id', $id)
        //     ->groupBy('siswa.user_id', 'users.username')
        //     ->get();

        // dd($kantor);
        $totalAktivitas = $jumlahDataTKJ + $jumlahDataRPL;

        $persentaseTKJ = $totalAktivitas > 0 ? ($jumlahDataTKJ / $totalAktivitas) * 100 : 0;
        $persentaseRPL = $totalAktivitas > 0 ? ($jumlahDataRPL / $totalAktivitas) * 100 : 0;

        $user_id = $request->input('user_id') ?? null;

        return view('dashboard', compact(
            'chartData',
            'activityData',
            'totalWaktuPerKategori',
            'jumlahDataRPL',
            'jumlahDataTKJ',
            'totalWaktu',
            'totalWaktuSemuaKategori',
            'persentaseWaktuPerKategori',
            'totalWaktuPerKategori',
            'rplCount',
            'tkjCount',
            'jumlahDataRPL',
            'jumlahDataTKJ',
            'totalWaktu',
            'persentaseTKJ',
            'persentaseRPL',
            'userList',
            'user_id'
        ));
    }

    public function getUserData($id)
    {
        $kategori = ['Belajar', 'Projek', 'DiKantor', 'Keluar dengan Teknisi'];

        $kantor = DB::table('siswa')
            ->join('users', 'siswa.user_id', '=', 'users.id')
            ->select('users.username', 'siswa.user_id', DB::raw('COUNT(siswa.kategori) as total_kategori'))
            ->whereIn('siswa.kategori', $kategori)
            ->where('user_id', $id)
            ->groupBy('siswa.user_id', 'users.username')
            ->get();

        dd($kantor);
        return $kantor;
    }
}
