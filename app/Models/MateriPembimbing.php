<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MateriPembimbing extends Model
{
    use HasFactory;
     
    protected $table = 'materi';

    protected $fillable = [
        'materi',
        'file_materi',
        'detail',
        'jurusan'
    ];
}