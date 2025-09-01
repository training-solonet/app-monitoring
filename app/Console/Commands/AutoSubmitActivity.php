<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AutoSubmitActivity extends Command
{
    protected $signature = 'activity:autosubmit';

    protected $description = 'Auto submit aktivitas yang masih berlangsung pada pukul 17.00 WIB';

    public function handle()
    {
        $now = Carbon::now('Asia/Jakarta'); // gunakan timezone Jakarta
        $jam = $now->format('H:i');

        if ($jam === '17:00') {
            // siswa kategori BUKAN "Keluar Dengan Teknisi"
            $query = DB::table('siswa')->where('kategori', '!=', 'Keluar Dengan Teknisi');
        } elseif ($jam === '21:00') {
            // siswa kategori "Keluar Dengan Teknisi"
            $query = DB::table('siswa')->where('kategori', 'Keluar Dengan Teknisi');
        } else {
            $this->info("Command activity:autosubmit hanya jalan jam 17:00 dan 21:00. Sekarang jam {$jam}");

            return;
        }

        // Update aktivitas dengan status "Sedang Berlangsung"
        $affectedBerlangsung = (clone $query)
            ->where('status', 'Sedang Berlangsung')
            ->update([
                'status' => 'Selesai',
                'waktu_selesai' => $now,
                'report' => 'Laporan disubmit otomatis oleh sistem.',
                'report_status' => 'Belum Lapor',
                'updated_at' => $now,
            ]);

        // Update aktivitas dengan status "Mulai"
        $affectedMulai = (clone $query)
            ->where('status', 'Mulai')
            ->update([
                'status' => 'Selesai',
                'waktu_mulai' => DB::raw('created_at'), // ambil dari created_at
                'waktu_selesai' => $now,
                'report' => 'Laporan disubmit otomatis oleh sistem.',
                'report_status' => 'Belum Lapor',
                'updated_at' => $now,
            ]);

        $total = $affectedBerlangsung + $affectedMulai;
        $this->info("Total $total aktivitas berhasil di-update. (Berlangsung: $affectedBerlangsung, Mulai: $affectedMulai)");
    }
}
