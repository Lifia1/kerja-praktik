<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bidang;
use App\Models\Boks;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Bidang
        $sekretariat = Bidang::create([
            'nama_bidang' => 'Sekretariat',
            'kode_bidang' => 'SEK',
        ]);

        $ipw = Bidang::create([
            'nama_bidang' => 'Infrastruktur dan Kewilayahan',
            'kode_bidang' => 'IPW',
        ]);

        $sda = Bidang::create([
            'nama_bidang' => 'Perekonomian dan Sumber Daya Alam',
            'kode_bidang' => 'SDA',
        ]);

        $ppm = Bidang::create([
            'nama_bidang' => 'Pemerintahan dan Pembangunan Manusia',
            'kode_bidang' => 'PPM',
        ]);

        // 2. Seed Users
        // Admin
        $admin = User::create([
            'name' => 'Administrator Sintara',
            'email' => 'admin@sintara.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'bidang_id' => null,
        ]);

        // Operator Sekretariat
        $opSek = User::create([
            'name' => 'Operator Sekretariat',
            'email' => 'operator@sintara.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'bidang_id' => $sekretariat->id,
        ]);

        // Operator IPW
        $opIpw = User::create([
            'name' => 'Operator IPW',
            'email' => 'operator2@sintara.com',
            'password' => Hash::make('password'),
            'role' => 'operator',
            'bidang_id' => $ipw->id,
        ]);

        // 3. Seed Boks
        $boks1 = Boks::create([
            'nomor_boks' => 'Boks-01',
            'lokasi_rak' => 'Rak A1',
            'keterangan' => 'Boks untuk surat penting umum',
        ]);

        $boks2 = Boks::create([
            'nomor_boks' => 'Boks-02',
            'lokasi_rak' => 'Rak A2',
            'keterangan' => 'Boks untuk surat dinas luar',
        ]);

        $boks3 = Boks::create([
            'nomor_boks' => 'Boks-03',
            'lokasi_rak' => 'Rak B1',
            'keterangan' => 'Boks arsip lama 2025',
        ]);

        // 4. Seed Surat Masuk
        SuratMasuk::create([
            'nomor_surat' => '005/123/SEK-2026',
            'tanggal_surat' => '2026-06-10',
            'tanggal_terima' => '2026-06-12',
            'pengirim' => 'Dinas Pendidikan Provinsi Lampung',
            'perihal' => 'Undangan Rapat Koordinasi Program Pendidikan',
            'boks_id' => $boks1->id,
            'user_id' => $opSek->id,
            'bidang_id' => $sekretariat->id,
            'scan_file' => null,
        ]);

        SuratMasuk::create([
            'nomor_surat' => '600/456/IPW-2026',
            'tanggal_surat' => '2026-06-15',
            'tanggal_terima' => '2026-06-16',
            'pengirim' => 'Kementerian PUPR',
            'perihal' => 'Penyampaian Dokumen Laporan Kinerja Infrastruktur',
            'boks_id' => $boks2->id,
            'user_id' => $opIpw->id,
            'bidang_id' => $ipw->id,
            'scan_file' => null,
        ]);

        // 5. Seed Surat Keluar
        SuratKeluar::create([
            'nomor_surat' => '090/512/Bappeda-2026',
            'tanggal_surat' => '2026-06-14',
            'tujuan' => 'Dinas Perhubungan Provinsi Lampung',
            'perihal' => 'Permintaan Data Lalu Lintas untuk Perencanaan Wilayah',
            'boks_id' => $boks1->id,
            'user_id' => $opSek->id,
            'bidang_id' => $sekretariat->id,
            'scan_file' => null,
        ]);

        SuratKeluar::create([
            'nomor_surat' => '050/623/IPW-Bappeda-2026',
            'tanggal_surat' => '2026-06-18',
            'tujuan' => 'Badan Pusat Statistik Provinsi Lampung',
            'perihal' => 'Koordinasi Sinkronisasi Data Infrastruktur Daerah',
            'boks_id' => $boks2->id,
            'user_id' => $opIpw->id,
            'bidang_id' => $ipw->id,
            'scan_file' => null,
        ]);
    }
}
