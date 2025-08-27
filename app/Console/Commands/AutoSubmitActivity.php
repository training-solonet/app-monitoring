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

        // Update aktivitas dengan status "Sedang Berlangsung"
        $affectedBerlangsung = DB::table('siswa')
            ->where('status', 'Sedang Berlangsung')
            ->update([
                'status' => 'Selesai',
                'waktu_selesai' => $now,
                'report' => 'Laporan disubmit otomatis oleh sistem.',
                'report_status' => 'Belum Lapor',
                'updated_at' => $now,
            ]);

        // Update aktivitas dengan status "Mulai"
        $affectedMulai = DB::table('siswa')
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
