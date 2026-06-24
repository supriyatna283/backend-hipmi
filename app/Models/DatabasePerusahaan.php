<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DatabasePerusahaan extends Model
{
    use HasFactory;

    protected $table = 'database_perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'nama_owner',
        'nik',
        'company_profile',
        'berkas_badan_hukum',
        'nib',
        'alamat_kantor',
        'bidang_usaha',
        'foto_produk',
        'logo_perusahaan',
        'nohp_owner',
        'nohp_perusahaan',
        'deskripsi',
    ];

    protected $casts = [
        'foto_produk' => 'array',
    ];
}
