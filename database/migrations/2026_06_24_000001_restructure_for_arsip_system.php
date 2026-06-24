<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Restrukturisasi database SINTARA sesuai ERD:
     * - Drop tabel surat_masuk, surat_keluar, boks (tidak dipakai)
     * - Buat tabel arsip baru sesuai ERD
     */
    public function up(): void
    {
        // Drop tabel lama yang tidak sesuai ERD
        Schema::dropIfExists('surat_keluar');
        Schema::dropIfExists('surat_masuk');
        Schema::dropIfExists('boks');

        // Buat tabel arsip sesuai ERD
        Schema::create('arsip', function (Blueprint $table) {
            $table->id();

            // Klasifikasi arsip
            $table->string('kode_klasifikasi')->comment('Kode klasifikasi arsip, contoh: 800.1.11.4');
            $table->string('no_berkas')->nullable()->comment('Nomor berkas/surat');
            $table->text('uraian_berkas')->comment('Uraian informasi berkas (judul dokumen)');
            $table->string('kurun_waktu')->nullable()->comment('Tahun/periode arsip');
            $table->string('jumlah_berkas')->nullable()->comment('Jumlah berkas, contoh: 1 berkas, 3 berkas');

            // Item arsip
            $table->string('no_item_arsip')->nullable()->comment('Nomor item arsip');
            $table->text('uraian_arsip')->nullable()->comment('Uraian isi arsip / uraian informasi arsip');

            // Tanggal dan fisik
            $table->date('tanggal_diarsipkan')->nullable()->comment('Tanggal dokumen diarsipkan');
            $table->string('jumlah_halaman_bundle')->nullable()->comment('Jumlah halaman/map/bundle, contoh: 5 halaman, 1 bundle');

            // Tingkat perkembangan dokumen
            $table->string('tingkat_perkembangan')->nullable()->comment('Asli, Fotokopi, Salinan, Tembusan, dll');

            // Lokasi fisik arsip
            $table->text('lokasi_simpan')->nullable()->comment('Keterangan lokasi simpan, contoh: Diarsipkan di Unit Pengolah Kearsipan Bappeda');
            $table->string('no_rak')->nullable()->comment('Nomor rak penyimpanan');
            $table->string('no_boks')->nullable()->comment('Nomor boks penyimpanan');
            $table->string('no_folder')->nullable()->comment('Nomor folder penyimpanan');

            // Klasifikasi keamanan (boolean per kategori)
            $table->boolean('is_biasa')->default(false)->comment('Klasifikasi keamanan: Biasa');
            $table->boolean('is_terbatas')->default(false)->comment('Klasifikasi keamanan: Terbatas');
            $table->boolean('is_rahasia')->default(false)->comment('Klasifikasi keamanan: Rahasia');
            $table->boolean('is_sangat_rahasia')->default(false)->comment('Klasifikasi keamanan: Sangat Rahasia');

            // Status retensi
            $table->boolean('is_aktif')->default(true)->comment('Status retensi aktif');
            $table->boolean('is_inaktif')->default(false)->comment('Status retensi inaktif');

            // Nasib akhir arsip
            $table->string('nasib_akhir')->nullable()->comment('Nasib akhir: Musnah, Permanen, Dinilai Kembali');

            // Scan file
            $table->string('scan_file')->nullable()->comment('Path file scan arsip');

            // Relasi
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index('kode_klasifikasi');
            $table->index('tanggal_diarsipkan');
            $table->index('no_rak');
            $table->index('no_boks');
            $table->index(['is_aktif', 'is_inaktif']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arsip');

        // Recreate boks table
        Schema::create('boks', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_boks')->unique();
            $table->string('lokasi_rak');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });

        // Recreate surat_masuk table
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->date('tanggal_terima');
            $table->string('pengirim');
            $table->string('perihal');
            $table->foreignId('boks_id')->constrained('boks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->string('scan_file')->nullable();
            $table->timestamps();
        });

        // Recreate surat_keluar table
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat');
            $table->date('tanggal_surat');
            $table->string('tujuan');
            $table->string('perihal');
            $table->foreignId('boks_id')->constrained('boks')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('bidang_id')->constrained('bidang')->cascadeOnDelete();
            $table->string('scan_file')->nullable();
            $table->timestamps();
        });
    }
};