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
        $now = now()->format('H:i');

        // Tentukan kondisi berdasarkan jam
        if ($now === '17:00') {
            // siswa kategori BUKAN "Keluar Dengan Teknisi"
            $query = Siswa::with('siswa_monitoring')
                ->where('kategori', '!=', 'Keluar Dengan Teknisi')
                ->where('report_status', 'Belum Lapor');
        } elseif ($now === '10:28') {
            // siswa kategori "Keluar Dengan Teknisi"
            $query = Siswa::with('siswa_monitoring')
                ->where('kategori', 'Keluar Dengan Teknisi')
                ->where(function ($q) {
                    $q->where('report_status', 'Belum Lapor')
                        ->orWhereNull('report_status');
                });
        } else {
            $this->info("Command reminder:whatsapp hanya jalan jam 17:00 dan 21:00. Sekarang jam {$now}");

            return;
        }

        // Ambil semua siswa yang belum lapor dan punya nomor HP
        $belumLapor = $query
            ->get()
            ->filter(fn ($siswa) => $siswa->siswa_monitoring && $siswa->siswa_monitoring->no_hp);

        // Buat array untuk menyimpan jumlah aktivitas per siswa
        $aktivitasPerSiswa = [];

        foreach ($belumLapor as $siswa) {
            $nickname = $siswa->siswa_monitoring->nickname;

            // Hitung jumlah aktivitas belum lapor per siswa

            if (!isset($aktivitasPerSiswa[$nickname])) {
                $aktivitasPerSiswa[$nickname] = 0;
            }
            $aktivitasPerSiswa[$nickname] += 1; // tambah 1 per aktivitas
        }

        // Kirim pesan untuk setiap siswa berdasarkan array
        foreach ($aktivitasPerSiswa as $nickname => $jumlahBelum) {
            // Ambil data siswa_monitoring dari salah satu siswa
            $siswaMonitor = $belumLapor->first(fn($s) => $s->siswa_monitoring->username === $nickname)->siswa_monitoring;

            $namaAkhir = substr($nickname, -1);
            $namaUnik = $nickname.$namaAkhir;

            $pesan = "Haii *{$namaUnik}*,\n\n"
                    ."Sistem kami menemukan bahwa kamu masih memiliki *{$jumlahBelum} aktivitas* yang belum dilaporkan. "
                    ."Setiap laporan sangat penting agar catatan kegiatanmu tetap lengkap dan sesuai aturan.\n\n"
                    .'Kami mengerti kalau kamu mungkin sibuk, tetapi jangan sampai laporan ini tertunda terlalu lama. '
                    ."Keterlambatan bisa memengaruhi penilaian PKL dan catatan performamu.\n\n"
                    ."Segera lengkapi laporanmu ya, agar semuanya tetap teratur dan perjalanan belajarmu lebih lancar.\n\n"
                    ."https://monitoring.connectis.my.id\n"
                    ."https://monitoring.connectis.my.id\n\n"
                    ."Terima kasih atas perhatian dan kerja kerasmu. 😁\n\n"
                    ."Salam hangat,\n*Sistem Monitoring*";

            Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $siswaMonitor->no_hp,
                'message' => $pesan,
            ]);

            $this->info("Pesan terkirim ke {$nickname} ({$siswaMonitor->no_hp}) dengan {$jumlahBelum} aktivitas belum dilaporkan.");
        }

    }
}
//
