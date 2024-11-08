<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class materi extends Model
{
    use HasFactory;

    protected $table = 'materi';

    protected $fillable = [
        'materi',
        'detail',
        'file_materi',
        'jurusan'
    ];

    // Relasi ke model Siswa (jika diperlukan)
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'materi_id');
    }
}
