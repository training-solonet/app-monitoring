<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Siswa extends Model
{
    use HasFactory;

    public function getWaktuMulaiAttribute($value)
    {
        return Carbon::parse($value);
    }
    
    protected $table = 'siswa'; 

    protected $fillable = [
        'kategori',
        'report',
        'waktu_mulai',
        'waktu_selesai',
    ];
}
