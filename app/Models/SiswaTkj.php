<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SiswaTkj extends Model
{
    use HasFactory;

    protected $table = 'tb_siswa_tkj'; 

    protected $fillable = [
        'report',
        'waktu_mulai',
        'waktu_selesai',
        'status',
        'kategori',
        'id_siswa'
    ];

}
