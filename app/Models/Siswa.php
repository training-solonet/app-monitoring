<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'kategori',
        'report',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'materi_id'
    ];

    protected $dates = [
        'waktu_mulai',
        'waktu_selesai'
    ];

    // Relasi ke model Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }
}
