<?php

namespace App\Console\Commands;

use App\Models\Siswa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class KirimReminderWhatsApp extends Command
{
    protected $signature = 'reminder:whatsapp';

    protected $description = 'Kirim reminder WhatsApp ke siswa yang belum lapor';

    public function handle()
    {
        // Ambil semua siswa yang belum lapor dan punya nomor HP
        $belumLapor = Siswa::with('siswa_monitoring')
            ->where('report_status', 'Belum Lapor')
            ->get()
            ->filter(fn ($siswa) => $siswa->siswa_monitoring && $siswa->siswa_monitoring->no_hp
            );

        if ($belumLapor->isEmpty()) {
            $this->info('Tidak ada siswa yang belum lapor atau tidak punya nomor HP.');

            return;
        }

        $jumlahBelum = 0;
        foreach ($belumLapor as $siswa) {
            // Kalau tabel siswa hanya punya 1 baris per siswa, jumlahnya pasti 1

            $jumlahBelum += 1;

            $nama = $siswa->siswa_monitoring->username;
            $namaAkhir = substr($nama, -1); // ambil huruf terakhir
            $namaUnik = $nama.$namaAkhir; // gandakan huruf terakhir

            $pesan = "Haii *{$namaUnik}*, "
                ."sistem mendeteksi bahwa kamu masih memiliki *{$jumlahBelum} aktivitas* yang belum dilaporkan.\n\n"
                .'Mohon segera melengkapi laporan kegiatanmu agar data dokumentasi tetap lengkap sesuai dengan ketentuan. '
                ."Keterlambatan dalam pelaporan *dapat memengaruhi penilaian PKL* serta laporan performa kamu.\n\n"
                .'Terima kasih atas perhatian dan kerja samanya. 😁';

        }
        Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target' => $siswa->siswa_monitoring->no_hp,
            'message' => $pesan,
        ]);

        $this->info("Pesan terkirim ke {$siswa->siswa_monitoring->username} ({$siswa->siswa_monitoring->no_hp})");
    }
}
//
