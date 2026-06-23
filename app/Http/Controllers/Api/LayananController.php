<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Layanan;

class LayananController extends Controller
{
    public function index()
    {
        return Layanan::where('is_active', '1')
            ->latest()
            ->select('id', 'nama', 'gambar_utama', 'slug', 'deskripsi')
            ->take(6)
            ->get();
    }

    public function show($slug)
    {
        $layanan = Layanan::where('is_active', '1')
            ->where('slug', $slug)
            ->firstOrFail();

        $layanan->gambar_utama = $layanan->gambar_utama
            ? asset('storage/' . $layanan->gambar_utama)
            : null;

        return $layanan;
    }
}
