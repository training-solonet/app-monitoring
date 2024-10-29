<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class siswa extends Model
{
    use HasFactory;

    protected $table ='Siswa';

    protected $fillable = [
        'kategori',
        'report',
        'waktu_mulai',
        'waktu_selesai',
        'status'
    ];

    protected $dates = [
        'waktu_mulai',
        'waktu_selesai'
    ];
}
