<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Hapus tabel peta_bisnis lama dan buat tabel database_perusahaan baru.
     */
    public function up(): void
    {
        // Hapus tabel peta_bisnis secara permanen
        Schema::dropIfExists('peta_bisnis');

        // Buat tabel database_perusahaan baru
        Schema::create('database_perusahaan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_perusahaan');
            $table->string('nama_owner');
            $table->string('nik', 20);
            $table->string('company_profile')->nullable(); // path file PDF/DOC
            $table->string('berkas_badan_hukum')->nullable(); // path file PDF
            $table->string('nib')->nullable(); // Nomor Induk Berusaha
            $table->text('alamat_kantor');
            $table->string('bidang_usaha');
            $table->json('foto_produk')->nullable(); // array of file paths
            $table->string('logo_perusahaan')->nullable(); // path file gambar
            $table->string('nohp_owner');
            $table->string('nohp_perusahaan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('database_perusahaan');

        // Buat kembali tabel peta_bisnis jika diperlukan rollback
        Schema::create('peta_bisnis', function (Blueprint $table) {
            $table->increments('id_usaha');
            $table->string('pj', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->string('nohp', 17)->nullable();
            $table->string('kta', 19)->nullable();
            $table->string('tempat_lahir', 20)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('nama_perusahaan', 55)->nullable();
            $table->string('tahun_berdiri', 44)->nullable();
            $table->string('badan_usaha', 18)->nullable();
            $table->text('sektor')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('tenaga_kerja', 19)->nullable();
            $table->string('modal_usaha', 29)->nullable();
        });
    }
};
