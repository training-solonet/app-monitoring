<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use App\Models\Materi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardRplController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil ID pengguna yang sedang login
        $userId = Auth::id();

        // Mengambil data siswa berdasarkan pengguna yang sedang login dan kategori Projek
        $totalWaktuProjek = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->get()
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

        // Mengelompokkan data siswa (kategori Projek) berdasarkan aktivitas_id
        $siswaData = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->get()
            ->groupBy('aktivitas_id')
            ->map(function ($items, $aktivitasId) use ($totalWaktuProjek) {
                $totalTime = 0;
                foreach ($items as $item) {
                    if ($item->waktu_mulai && $item->waktu_selesai) {
                        $waktuMulai = Carbon::parse($item->waktu_mulai);
                        $waktuSelesai = Carbon::parse($item->waktu_selesai);
                        if ($waktuSelesai->greaterThan($waktuMulai)) {
                            $totalTime += $waktuSelesai->diffInSeconds($waktuMulai);
                        }
                    }
                }
                $percentage = $totalWaktuProjek ? ($totalTime / $totalWaktuProjek) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        // Mengambil nama aktivitas berdasarkan aktivitas_id yang ada di $siswaData.
        $aktivitasNames = Aktivitas::whereIn('id', $siswaData->keys())->pluck('nama_aktivitas', 'id');

        $jumlahAktivitas = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->get()
            ->groupBy('aktivitas_id')
            ->map->count();

        // Mengambil data siswa berdasarkan pengguna yang sedang login dan kategori Belajar.
        $totalWaktuBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->get()
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

        // Menghitung total waktu untuk setiap materi dan persentasenya terhadap total waktu belajar.
        $siswaDataBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->get()
            ->groupBy('materi_id')
            ->map(function ($items, $materiId) use ($totalWaktuBelajar) {
                $totalTime = 0;
                foreach ($items as $item) {
                    if ($item->waktu_mulai && $item->waktu_selesai) {
                        $waktuMulai = Carbon::parse($item->waktu_mulai);
                        $waktuSelesai = Carbon::parse($item->waktu_selesai);
                        if ($waktuSelesai->greaterThan($waktuMulai)) {
                            $totalTime += $waktuSelesai->diffInSeconds($waktuMulai);
                        }
                    }
                }
                $percentage = $totalWaktuBelajar ? ($totalTime / $totalWaktuBelajar) * 100 : 0;

                return ['totalTime' => $totalTime, 'percentage' => $percentage];
            });

        // Mengambil nama materi berdasarkan materi_id yang ada di $siswaDataBelajar.
        $materiNames = Materi::whereIn('id', $siswaDataBelajar->keys())->orderByRaw('FIELD(id, '.implode(',', $siswaDataBelajar->keys()->toArray()).')')->pluck('materi', 'id');
        // Memperbaiki supaya keys diurutkan ascending

        // Menghitung jumlah data (frekuensi) untuk setiap materi berdasarkan materi_id.
        $jumlahAktivitasBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->get()
            ->groupBy('materi_id')
            ->map->count();

        // Menghitung jumlah data siswa untuk kategori Projek dan Belajar.
        $jumlahDataProjek = Siswa::where('user_id', $userId)
            ->where('kategori', 'Projek')
            ->count();
        $jumlahDataBelajar = Siswa::where('user_id', $userId)
            ->where('kategori', 'Belajar')
            ->count();

        // Menghitung total waktu dari semua aktivitas (baik Projek maupun Belajar).
        $totalWaktu = Siswa::where('user_id', $userId)
            ->get()
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

        // Menghitung total aktivitas (Belajar + Projek).
        $totalAktivitas = $jumlahDataBelajar + $jumlahDataProjek;
        // Menghitung persentase aktivitas Belajar terhadap total aktivitas.
        $persentaseBelajar = $totalAktivitas > 0 ? ($jumlahDataBelajar / $totalAktivitas) * 100 : 0;
        // Menghitung persentase aktivitas Projek terhadap total aktivitas.
        $persentaseProjek = $totalAktivitas > 0 ? ($jumlahDataProjek / $totalAktivitas) * 100 : 0;

        // Membuat array yang memetakan nama materi dengan jumlah aktivitasnya.
        $dataAktivitasBelajar = [
            'name' => [],
            'jumlah' => [],
        ];

        $i = 0;
        foreach ($materiNames as $key => $val) {
            $dataAktivitasBelajar['name'][$i] = $val;
            $dataAktivitasBelajar['jumlah'][$i] = $jumlahAktivitasBelajar[$key];
            $i++;
        }

        $belumLapor = DB::table('siswa')
            ->where('user_id', Auth::id())
            ->where('report_status', 'Belum Lapor')
            ->count();

        return view('dashboardrpl', compact(
            'siswaData',
            'aktivitasNames',
            'totalWaktuProjek',
            'jumlahAktivitas',
            'siswaDataBelajar',
            'materiNames',
            'totalWaktuBelajar',
            'jumlahAktivitasBelajar',
            'jumlahDataBelajar',
            'jumlahDataProjek',
            'totalWaktu',
            'persentaseBelajar',
            'persentaseProjek',
            'dataAktivitasBelajar',
            'belumLapor'
        ));
    }
}
