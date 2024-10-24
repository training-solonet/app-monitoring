<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaRpl extends Model
{
    use HasFactory;

    protected $table = 'tb_siswa_rpl'; 

    protected $fillable = [
        'kategori',
        'report',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'id_siswa'
    ];

}
