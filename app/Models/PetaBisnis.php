<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PetaBisnis extends Model
{
    use HasFactory;

    protected $table = 'peta_bisnis';
    protected $primaryKey = 'id_usaha';
    public $timestamps = false; // <--- tambahkan ini

    protected $fillable = [
        'id_usaha',
        'pj',
        'email',
        'nohp',
        'kta',
        'tempat_lahir',
        'tgl_lahir',
        'alamat',
        'nama_perusahaan',
        'tahun_berdiri',
        'badan_usaha',
        'sektor',
        'deskripsi',
        'tenaga_kerja',
        'modal_usaha',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
    ];
}
