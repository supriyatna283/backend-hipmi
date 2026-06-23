<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;

class ArtikelController extends Controller
{
    public function index()
    {
        return Artikel::where('status', 'publish')
            ->latest()
            ->select('id', 'judul', 'slug', 'gambar', 'created_at', 'reading_time')
            ->take(6)
            ->get();
    }

    public function show($slug)
    {
        $artikel = Artikel::where('status', 'publish')
            ->where('slug', $slug)
            ->firstOrFail();

        $artikel->gambar = $artikel->gambar
            ? asset('storage/' . $artikel->gambar)
            : null;

        return $artikel;
    }
}
