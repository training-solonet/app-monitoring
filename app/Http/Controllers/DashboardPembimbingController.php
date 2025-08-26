<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardPembimbingController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil daftar pengguna dengan role siswa
        $userList = User::all();
        $userList = User::where('role', 'siswa')->where('status', 'Aktif')->get();

        // Menghitung jumlah data materi sesuai jurusan
        $rplCount = Materi::where('jurusan', 'RPL')->count();
        $tkjCount = Materi::where('jurusan', 'TKJ')->count();

        // Menyiapkan data dalam format yang digunakan untuk membuat grafik (chart), seperti pie chart
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

        // Menghitung jumlah aktivitas siswa berdasarkan kategori, dan jika parameter user_id diberikan, hanya mengambil data untuk pengguna tersebut.
        $activityData = Siswa::select('kategori')
            ->selectRaw('COUNT(*) as count')
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->groupBy('kategori')
            ->pluck('count', 'kategori');

        // Jika $activityData kosong, set nilai default
        if ($activityData->isEmpty()) {
            $activityData = collect([]);
        }

        // Mengambil jumlah siswa yang terkait dengan setiap materi (materi_id) dan mengelompokkan berdasarkan materi_id.
        $materiData = Siswa::with('data_materi')
            ->selectRaw('COUNT(*) as count, materi_id')
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->groupBy('materi_id')
            ->get();

        // buat array sesuai format chart
        $data_grafik_pie_chart = [];
        foreach ($materiData as $item) {
            if (! empty($item->data_materi)) { // Cek apakah ada data materi
                $data_grafik_pie_chart[] = [
                    'total' => $item->count,
                    'materi' => $item->data_materi->materi ?? 'Data Tidak Tersedia',
                ];
            }
        }

        // Mengubah format data
        // Mengubah format data
        $formattedData = [];
        foreach ($data_grafik_pie_chart as $item) {
            // Ubah nama materi menjadi lowercase dan masukkan ke dalam array dengan total sebagai value
            $formattedData[strtolower($item['materi'])] = $item['total'];
        }

        // Mengambil kategori Siswa Rpl
        $jumlahDataRPL = Siswa::whereIn('kategori', ['Belajar', 'Projek'])
            ->count();

        $jumlahDataRPL = $jumlahDataRPL ?? 0;
        $totalAktivitas = $totalAktivitas ?? 1; // Hindari pembagian dengan nol
        $persentaseRPL = ($jumlahDataRPL / $totalAktivitas) * 100;

        // Mengambil kategori Siswa Tkj
        $jumlahDataTKJ = Siswa::whereIn('kategori', ['Dikantor', 'Keluar Dengan Teknisi'])
            ->count();

        // Mengambil Total waktu Siswa
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

        // Menghitung total waktu siswa berdasarkan kategori, dan jika user_id diberikan, hanya menghitung untuk pengguna tersebut.
        $totalWaktuPerKategori = Siswa::select('kategori')
            ->selectRaw('SUM(TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_selesai)) as total_waktu')
            ->when($request->has('user_id'), function ($query) use ($request) {
                return $query->where('user_id', $request->input('user_id'));
            })
            ->groupBy('kategori')
            ->pluck('total_waktu', 'kategori');

        $totalWaktuSemuaKategori = $totalWaktuPerKategori->sum();

        //  Menghitung persentase waktu setiap kategori dibandingkan total waktu semua kategori.
        $persentaseWaktuPerKategori = $totalWaktuPerKategori->map(function ($waktu) use ($totalWaktuSemuaKategori) {
            return $totalWaktuSemuaKategori > 0 ? ($waktu / $totalWaktuSemuaKategori) * 100 : 0;
        });

        // Menjumlahkan total aktivitas siswa dari kategori RPL dan TKJ
        $totalAktivitas = $jumlahDataTKJ + $jumlahDataRPL;

        // Menghitung persentase aktivitas TKJ dan RPL dibandingkan total aktivitas.
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
            'formattedData',
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

        return $kantor;
    }
}
