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
            ->filter(fn ($siswa) => $siswa->siswa_monitoring && $siswa->siswa_monitoring->no_hp);

        // Buat array untuk menyimpan jumlah aktivitas per siswa
        $aktivitasPerSiswa = [];

        foreach ($belumLapor as $siswa) {
            $username = $siswa->siswa_monitoring->username;

            // Hitung jumlah aktivitas belum lapor per siswa
            if (!isset($aktivitasPerSiswa[$username])) {
                $aktivitasPerSiswa[$username] = 0;
            }
            $aktivitasPerSiswa[$username] += 1; // tambah 1 per aktivitas
        }

        // Kirim pesan untuk setiap siswa berdasarkan array
        foreach ($aktivitasPerSiswa as $username => $jumlahBelum) {
            // Ambil data siswa_monitoring dari salah satu siswa
            $siswaMonitor = $belumLapor->first(fn($s) => $s->siswa_monitoring->username === $username)->siswa_monitoring;

            $namaAkhir = substr($username, -1);
            $namaUnik = $username.$namaAkhir;

            $pesan = "Haii *{$namaUnik}*,\n\n"
                    ."Sistem kami menemukan bahwa kamu masih memiliki *{$jumlahBelum} aktivitas* yang belum dilaporkan. "
                    ."Setiap laporan sangat penting agar catatan kegiatanmu tetap lengkap dan sesuai aturan.\n\n"
                    ."Kami mengerti kalau kamu mungkin sibuk, tetapi jangan sampai laporan ini tertunda terlalu lama. "
                    ."Keterlambatan bisa memengaruhi penilaian PKL dan catatan performamu.\n\n"
                    ."Segera lengkapi laporanmu ya, agar semuanya tetap teratur dan perjalanan belajarmu lebih lancar.\n\n"
                    ."Terima kasih atas perhatian dan kerja kerasmu. 😁\n\n"
                    ."Salam hangat,\n*Sistem Monitoring*";

            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $siswaMonitor->no_hp,
                'message' => $pesan,
            ]);

            $this->info("Pesan terkirim ke {$username} ({$siswaMonitor->no_hp}) dengan {$jumlahBelum} aktivitas belum dilaporkan.");
        }

    }
}
//
