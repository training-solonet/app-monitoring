<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'user_id'
    ];

    protected $dates = [
        'waktu_mulai',
        'waktu_selesai'
    ];

    public function materitkj()
    {
        return $this->belongsTo(Materi::class, 'materi_id');
    }


    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
