<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PetaBisnis;
use Illuminate\Support\Facades\Validator;

class PetaBisnisController extends Controller
{
    /**
     * Display a listing of the resource.
     * Mengambil dan menampilkan semua data PetaBisnis.
     */
    public function index()
    {
        // Mengambil semua data dari model PetaBisnis.
        // Data diurutkan secara alfabetis berdasarkan nama_perusahaan.
        $petaBisnis = PetaBisnis::orderBy('nama_perusahaan', 'asc')->get();

        // Mengubah nilai nama_perusahaan, sektor, dan deskripsi.
        $petaBisnis = $petaBisnis->map(function ($item) {
            $item->nama_perusahaan = mb_strtoupper($item->nama_perusahaan);
            $item->deskripsi = mb_strtoupper($item->deskripsi);

            // Logika untuk mengambil sektor pertama.
            // Memeriksa apakah kolom 'sektor' ada dan merupakan string.
            if (isset($item->sektor) && is_string($item->sektor)) {
                // Memisahkan string berdasarkan koma dan spasi.
                $sectors = explode(',', $item->sektor);
                // Mengambil elemen pertama, membersihkan spasi di awal/akhir, dan mengubahnya menjadi huruf kapital.
                $item->sektor = mb_strtoupper(trim($sectors[0]));
            } else {
                // Jika tidak ada data sektor, atur nilai ke null.
                $item->sektor = null;
            }

            return $item;
        });

        // Mengembalikan respons dalam format JSON.
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => $petaBisnis,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan data baru ke dalam database.
     */
    public function store(Request $request)
    {
        // Validasi input dari request.
        $validator = Validator::make($request->all(), [
            'pj' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:peta_bisnis',
            'nohp' => 'required|string|max:255',
            'kta' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_perusahaan' => 'required|string|max:255',
            'tahun_berdiri' => 'nullable|integer',
            'badan_usaha' => 'nullable|string|max:255',
            'sektor' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tenaga_kerja' => 'nullable|integer',
            'modal_usaha' => 'nullable|integer',
        ]);

        // Jika validasi gagal, kembalikan respons error.
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Membuat instance baru dari model PetaBisnis dan menyimpannya.
        $petaBisnis = PetaBisnis::create($request->all());

        // Mengembalikan respons sukses dengan data yang baru dibuat.
        return response()->json([
            'status' => 'success',
            'message' => 'Data created successfully.',
            'data' => $petaBisnis,
        ], 201);
    }

    /**
     * Display the specified resource.
     * Mengambil dan menampilkan satu data berdasarkan 'id_usaha'.
     */
    public function show(string $id_usaha)
    {
        // Cari data PetaBisnis berdasarkan 'id_usaha'.
        $petaBisnis = PetaBisnis::find($id_usaha);

        // Jika data tidak ditemukan, kembalikan respons 404.
        if (!$petaBisnis) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404);
        }

        // Mengembalikan data yang ditemukan.
        return response()->json([
            'status' => 'success',
            'message' => 'Data retrieved successfully.',
            'data' => $petaBisnis,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     * Memperbarui data yang sudah ada.
     */
    public function update(Request $request, string $id_usaha)
    {
        // Cari data PetaBisnis berdasarkan 'id_usaha'.
        $petaBisnis = PetaBisnis::find($id_usaha);

        // Jika data tidak ditemukan, kembalikan respons 404.
        if (!$petaBisnis) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404);
        }

        // Validasi input dari request.
        $validator = Validator::make($request->all(), [
            'pj' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:peta_bisnis,email,' . $id_usaha . ',id_usaha',
            'nohp' => 'nullable|string|max:255',
            'kta' => 'nullable|string|max:255',
            'tempat_lahir' => 'nullable|string|max:255',
            'tgl_lahir' => 'nullable|date',
            'alamat' => 'nullable|string',
            'nama_perusahaan' => 'nullable|string|max:255',
            'tahun_berdiri' => 'nullable|integer',
            'badan_usaha' => 'nullable|string|max:255',
            'sektor' => 'nullable|string|max:255',
            'deskripsi' => 'nullable|string',
            'tenaga_kerja' => 'nullable|integer',
            'modal_usaha' => 'nullable|integer',
        ]);

        // Jika validasi gagal, kembalikan respons error.
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Perbarui data.
        $petaBisnis->update($request->all());

        // Mengembalikan respons sukses dengan data yang diperbarui.
        return response()->json([
            'status' => 'success',
            'message' => 'Data updated successfully.',
            'data' => $petaBisnis,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     * Menghapus data dari database.
     */
    public function destroy(string $id_usaha)
    {
        // Cari data PetaBisnis berdasarkan 'id_usaha'.
        $petaBisnis = PetaBisnis::find($id_usaha);

        // Jika data tidak ditemukan, kembalikan respons 404.
        if (!$petaBisnis) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found.',
            ], 404);
        }

        // Hapus data.
        $petaBisnis->delete();

        // Mengembalikan respons sukses.
        return response()->json([
            'status' => 'success',
            'message' => 'Data deleted successfully.',
        ], 200);
    }
}
