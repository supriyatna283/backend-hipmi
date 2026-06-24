<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DatabasePerusahaan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DatabasePerusahaanController extends Controller
{
    /**
     * Mengambil dan menampilkan semua data perusahaan.
     */
    public function index()
    {
        $perusahaan = DatabasePerusahaan::orderBy('nama_perusahaan', 'asc')->get();

        // Tambahkan URL lengkap untuk setiap file
        $perusahaan = $perusahaan->map(function ($item) {
            $item->nama_perusahaan = mb_strtoupper($item->nama_perusahaan);
            $item->bidang_usaha = mb_strtoupper($item->bidang_usaha);

            // Buat URL publik untuk file-file
            if ($item->logo_perusahaan) {
                $item->logo_perusahaan_url = Storage::url($item->logo_perusahaan);
            }
            if ($item->company_profile) {
                $item->company_profile_url = Storage::url($item->company_profile);
            }
            if ($item->berkas_badan_hukum) {
                $item->berkas_badan_hukum_url = Storage::url($item->berkas_badan_hukum);
            }
            if ($item->foto_produk && is_array($item->foto_produk)) {
                $item->foto_produk_urls = array_map(fn($path) => Storage::url($path), $item->foto_produk);
            }

            return $item;
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Data retrieved successfully.',
            'data'    => $perusahaan,
        ], 200);
    }

    /**
     * Menyimpan data perusahaan baru ke dalam database.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_perusahaan'   => 'required|string|max:255',
            'nama_owner'        => 'required|string|max:255',
            'nik'               => 'required|string|max:20',
            'alamat_kantor'     => 'required|string',
            'bidang_usaha'      => 'required|string|max:255',
            'nohp_owner'        => 'required|string|max:20',
            'nohp_perusahaan'   => 'nullable|string|max:20',
            'nib'               => 'nullable|string|max:50',
            'company_profile'   => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'berkas_badan_hukum'=> 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'logo_perusahaan'   => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:5120',
            'foto_produk'       => 'nullable|array',
            'foto_produk.*'     => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->except(['company_profile', 'berkas_badan_hukum', 'logo_perusahaan', 'foto_produk']);

        // Handle upload company_profile
        if ($request->hasFile('company_profile')) {
            $data['company_profile'] = $request->file('company_profile')->store('perusahaan/company_profile', 'public');
        }

        // Handle upload berkas_badan_hukum
        if ($request->hasFile('berkas_badan_hukum')) {
            $data['berkas_badan_hukum'] = $request->file('berkas_badan_hukum')->store('perusahaan/berkas_badan_hukum', 'public');
        }

        // Handle upload logo_perusahaan
        if ($request->hasFile('logo_perusahaan')) {
            $data['logo_perusahaan'] = $request->file('logo_perusahaan')->store('perusahaan/logo', 'public');
        }

        // Handle upload multiple foto_produk
        if ($request->hasFile('foto_produk')) {
            $fotoPaths = [];
            foreach ($request->file('foto_produk') as $foto) {
                $fotoPaths[] = $foto->store('perusahaan/foto_produk', 'public');
            }
            $data['foto_produk'] = $fotoPaths;
        }

        $perusahaan = DatabasePerusahaan::create($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data perusahaan berhasil disimpan.',
            'data'    => $perusahaan,
        ], 201);
    }

    /**
     * Mengambil satu data perusahaan berdasarkan ID.
     */
    public function show(string $id)
    {
        $perusahaan = DatabasePerusahaan::find($id);

        if (!$perusahaan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        // Tambahkan URL file
        if ($perusahaan->logo_perusahaan) {
            $perusahaan->logo_perusahaan_url = Storage::url($perusahaan->logo_perusahaan);
        }
        if ($perusahaan->foto_produk && is_array($perusahaan->foto_produk)) {
            $perusahaan->foto_produk_urls = array_map(fn($path) => Storage::url($path), $perusahaan->foto_produk);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diambil.',
            'data'    => $perusahaan,
        ], 200);
    }

    /**
     * Memperbarui data perusahaan yang sudah ada.
     */
    public function update(Request $request, string $id)
    {
        $perusahaan = DatabasePerusahaan::find($id);

        if (!$perusahaan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_perusahaan'   => 'nullable|string|max:255',
            'nama_owner'        => 'nullable|string|max:255',
            'nik'               => 'nullable|string|max:20',
            'alamat_kantor'     => 'nullable|string',
            'bidang_usaha'      => 'nullable|string|max:255',
            'nohp_owner'        => 'nullable|string|max:20',
            'nohp_perusahaan'   => 'nullable|string|max:20',
            'nib'               => 'nullable|string|max:50',
            'company_profile'   => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'berkas_badan_hukum'=> 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'logo_perusahaan'   => 'nullable|file|mimes:jpg,jpeg,png,svg,webp|max:5120',
            'foto_produk'       => 'nullable|array',
            'foto_produk.*'     => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov|max:20480',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $data = $request->except(['company_profile', 'berkas_badan_hukum', 'logo_perusahaan', 'foto_produk']);

        // Handle upload file baru (hapus file lama jika ada)
        if ($request->hasFile('company_profile')) {
            if ($perusahaan->company_profile) Storage::disk('public')->delete($perusahaan->company_profile);
            $data['company_profile'] = $request->file('company_profile')->store('perusahaan/company_profile', 'public');
        }

        if ($request->hasFile('berkas_badan_hukum')) {
            if ($perusahaan->berkas_badan_hukum) Storage::disk('public')->delete($perusahaan->berkas_badan_hukum);
            $data['berkas_badan_hukum'] = $request->file('berkas_badan_hukum')->store('perusahaan/berkas_badan_hukum', 'public');
        }

        if ($request->hasFile('logo_perusahaan')) {
            if ($perusahaan->logo_perusahaan) Storage::disk('public')->delete($perusahaan->logo_perusahaan);
            $data['logo_perusahaan'] = $request->file('logo_perusahaan')->store('perusahaan/logo', 'public');
        }

        if ($request->hasFile('foto_produk')) {
            // Hapus foto lama
            if ($perusahaan->foto_produk && is_array($perusahaan->foto_produk)) {
                foreach ($perusahaan->foto_produk as $oldPath) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $fotoPaths = [];
            foreach ($request->file('foto_produk') as $foto) {
                $fotoPaths[] = $foto->store('perusahaan/foto_produk', 'public');
            }
            $data['foto_produk'] = $fotoPaths;
        }

        $perusahaan->update($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil diperbarui.',
            'data'    => $perusahaan->fresh(),
        ], 200);
    }

    /**
     * Menghapus data perusahaan dari database.
     */
    public function destroy(string $id)
    {
        $perusahaan = DatabasePerusahaan::find($id);

        if (!$perusahaan) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        // Hapus file-file terkait dari storage
        if ($perusahaan->company_profile) Storage::disk('public')->delete($perusahaan->company_profile);
        if ($perusahaan->berkas_badan_hukum) Storage::disk('public')->delete($perusahaan->berkas_badan_hukum);
        if ($perusahaan->logo_perusahaan) Storage::disk('public')->delete($perusahaan->logo_perusahaan);
        if ($perusahaan->foto_produk && is_array($perusahaan->foto_produk)) {
            foreach ($perusahaan->foto_produk as $path) {
                Storage::disk('public')->delete($path);
            }
        }

        $perusahaan->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Data berhasil dihapus.',
        ], 200);
    }
}
