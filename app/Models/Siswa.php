<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';

    protected $fillable = [
        'kategori',
        'report',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'materi_id',
        'user_id',
        'aktivitas_id',
        'bukti',
    ];

    protected $dates = [
        'waktu_mulai',
        'waktu_selesai',
    ];

    protected $casts = [
        'bukti' => 'array',
    ];

    public function materitkj()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }

    public function aktivitas()
    {
        return $this->belongsTo(Aktivitas::class, 'aktivitas_id');
    }

    public function siswa_monitoring()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function materi()
    {
        return $this->belongsToMany(Materi::class, 'siswa_materi', 'siswa_id', 'materi_id');
    }

    public function data_materi()
    {
        return $this->hasOne(Materi::class, 'id', 'materi_id');
    }
}
