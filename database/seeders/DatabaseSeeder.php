<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Bidang;
use App\Models\Arsip;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Data sesuai dengan struktur ERD SINTARA dan contoh data arsip aktif Sekretariat.
     */
    public function run(): void
    {
        // ─── 1. Seed Bidang ───────────────────────────────────────────────────
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

        $pme = Bidang::create([
            'nama_bidang' => 'Perencanaan, Monitoring dan Evaluasi',
            'kode_bidang' => 'PME',
        ]);

        // ─── 2. Seed Users ────────────────────────────────────────────────────
        $admin = User::create([
            'name'      => 'Administrator SINTARA',
            'email'     => 'admin@sintara.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'bidang_id' => null,
        ]);

        $opSek = User::create([
            'name'      => 'Operator Sekretariat',
            'email'     => 'operator@sintara.com',
            'password'  => Hash::make('password'),
            'role'      => 'operator',
            'bidang_id' => $sekretariat->id,
        ]);

        $opIpw = User::create([
            'name'      => 'Operator IPW',
            'email'     => 'operator2@sintara.com',
            'password'  => Hash::make('password'),
            'role'      => 'operator',
            'bidang_id' => $ipw->id,
        ]);

        // ─── 3. Seed Arsip ────────────────────────────────────────────────────
        // Data diambil dari DAFTAR_ARSIP_AKTIF_MEI_2026.pdf (Sekretariat)
        // Lokasi: Rak 3, Boks 2 (mayoritas arsip Sekretariat)

        $arsipData = [
            [
                'kode_klasifikasi'     => '000',
                'no_berkas'            => '000/7/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 000/7/VI.01/2026 hal Penyampaian Surat Keputusan Standar Pelayanan, Visi, Misi, Motto, Reward and Punishment pada Bappeda Provinsi Lampung',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Umum',
                'tanggal_diarsipkan'   => '2026-05-11',
                'jumlah_halaman_bundle' => '1 bundle',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => true,
            ],
            [
                'kode_klasifikasi'     => '000',
                'no_berkas'            => '00042/VI.01/2026',
                'uraian_berkas'        => 'Surat Keputusan Gubernur Lampung Nomor 00042/VI.01/2026 tentang Kompensasi Pelayanan Pada Bappeda Provinsi Lampung',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Umum',
                'tanggal_diarsipkan'   => '2026-05-13',
                'jumlah_halaman_bundle' => '10 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '000.3',
                'no_berkas'            => '000.3/3/05/2025',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 000.3/3/05/2025 perihal Permintaan Penyampaian Daftar Penyedia',
                'kurun_waktu'          => '2025',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Pengadaan',
                'tanggal_diarsipkan'   => '2026-02-24',
                'jumlah_halaman_bundle' => '5 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => true,
            ],
            [
                'kode_klasifikasi'     => '000.3',
                'no_berkas'            => '000.3/6/05/2026',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 000.3/6/05/2026 hal Undangan Peningkatan Kompetensi Pelaku Pengadaan Barang/Jasa tentang Konsolidasi Paket Pengadaan dan Teknis Pengadaan Melalui E-Purchasing',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Pengadaan',
                'tanggal_diarsipkan'   => '2026-05-11',
                'jumlah_halaman_bundle' => '5 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '000.5',
                'no_berkas'            => null,
                'uraian_berkas'        => 'DOKUMEN RISALAH HASIL AUDIT KEARSIPAN INTERNAL SEMENTARA SEKRETARIAT 2022',
                'kurun_waktu'          => '2022',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Kearsipan',
                'tanggal_diarsipkan'   => '2026-04-07',
                'jumlah_halaman_bundle' => '1 bundle',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => true,
            ],
            [
                'kode_klasifikasi'     => '000.5',
                'no_berkas'            => null,
                'uraian_berkas'        => 'DOKUMEN RISALAH HASIL AUDIT KEARSIPAN INTERNAL SEMENTARA SEKRETARIAT 2023',
                'kurun_waktu'          => '2023',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Kearsipan',
                'tanggal_diarsipkan'   => '2026-04-07',
                'jumlah_halaman_bundle' => '1 bundle',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => true,
            ],
            [
                'kode_klasifikasi'     => '000.5.3',
                'no_berkas'            => null,
                'uraian_berkas'        => 'Kumpulan Berkas Kearsipan terkait Bimbingan Teknis Kearsipan dengan Tema "Optimalisasi Pengelolaan Arsip Dalam Mendukung Efisiensi Kerja dan Kepatuhan Regulasi"',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Pembinaan Kearsipan',
                'tanggal_diarsipkan'   => '2026-03-03',
                'jumlah_halaman_bundle' => '1 map',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '000.8.3',
                'no_berkas'            => '000.8.3/12/07/2026',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 000.8.3/12/07/2026 perihal Penataan Nomenklatur, Tugas dan Fungsi Perangkat Daerah di Lingkungan Pemerintah Provinsi Lampung',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Ketatalaksanaan',
                'tanggal_diarsipkan'   => '2026-02-24',
                'jumlah_halaman_bundle' => '4 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => true,
                'nasib_akhir'          => 'Permanen',
            ],
            [
                'kode_klasifikasi'     => '000.8.3.4',
                'no_berkas'            => '000.8.3.4/3/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 000.8.3.4/3/VI.01/2026 perihal Keputusan Gubernur Lampung tentang Pengelola Aplikasi Survei Kepuasan Masyarakat Secara Daring di Lingkungan Badan Perencanaan Pembangunan Daerah Provinsi Lampung Tahun 2026',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Pelayanan Publik',
                'tanggal_diarsipkan'   => '2026-03-03',
                'jumlah_halaman_bundle' => '14 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '700',
                'no_berkas'            => '700/569/IV.01/20/2024',
                'uraian_berkas'        => 'Surat INSPEKTORAT Provinsi Lampung Nomor 700/569/IV.01/20/2024 Perihal Permintaan Nama Asesor Dalam Penilaian Mandiri Sistem Pengendalian Intern Pemerintah Terpadu (SPIPT) Tingkat Pemerintah Provinsi Lampung',
                'kurun_waktu'          => '2024',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Pengawasan',
                'tanggal_diarsipkan'   => '2026-02-23',
                'jumlah_halaman_bundle' => '3 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '1',
                'no_boks'              => '1',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '800',
                'no_berkas'            => '800/1/VI.04/2026',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 800/1/VI.04/2026 perihal Undangan Rapat Penyampaian Teknis Penilaian 360 derajat Pegawai Aparatur Sipil Negara di Lingkungan Pemerintah Provinsi Lampung',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Kepegawaian',
                'tanggal_diarsipkan'   => '2026-02-25',
                'jumlah_halaman_bundle' => '1 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '1',
                'no_boks'              => '1',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '800.1.3.2',
                'no_berkas'            => '800.1.3.2/84/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 800.1.3.2/84/VI.01/2026 hal Usul Kenaikan Jenjang Jabatan Perencana Ahli Madya a.n. MERYLIA, S.T.,M.T..M.Sc',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Kenaikan Pangkat/Golongan/Jabatan',
                'tanggal_diarsipkan'   => '2026-03-09',
                'jumlah_halaman_bundle' => '7 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '1',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '800.1.11.4',
                'no_berkas'            => '800.1.11.4/42/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 800.1.11.4/42/VI.01/2026 perihal Surat Izin Cuti Tahunan a.n. ANDI ARAFAT, S.T.,M.E cuti dari tanggal 13-27 Februari 2026',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Cuti Tahunan',
                'tanggal_diarsipkan'   => '2026-02-23',
                'jumlah_halaman_bundle' => '6 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '2',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '800.1.11.4',
                'no_berkas'            => '800.1.11.4/30/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 800.1.11.4/30/VI.01/2026 perihal Surat Izin Cuti Tahunan a.n. NAFIAH PRATIWI, S.IP cuti dari tanggal 29 Mei dan 02-05 Juni 2026',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Cuti Tahunan',
                'tanggal_diarsipkan'   => '2026-05-11',
                'jumlah_halaman_bundle' => '6 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '2',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '800.1.4.1',
                'no_berkas'            => '800.1.4.1/41/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 800.1.4.1/41/VI.01/2026 Surat Rekomendasi a.n. SITI MASITOH, SARAH NABILA PUTRI, M. FAREZA AKBAR melanjutkan Izin Pendidikan',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Usulan Tugas Belajar/Ijin Belajar/Diklat/Kursus',
                'tanggal_diarsipkan'   => '2026-04-08',
                'jumlah_halaman_bundle' => '3 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '1',
                'no_boks'              => '3',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '900.1',
                'no_berkas'            => '900.1/22/VI.01/2026',
                'uraian_berkas'        => 'Surat Bappeda Provinsi Lampung Nomor 900.1/22/VI.01/2026 tentang Penetapan Operator Perencanaan Aplikasi Sistem Informasi Pemerintahan Daerah (SIPD) RI Pada Badan Perencanaan Pembangunan Daerah Provinsi Lampung Tahun Anggaran 2026',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '2 berkas',
                'uraian_arsip'         => 'Keuangan Daerah',
                'tanggal_diarsipkan'   => '2026-03-03',
                'jumlah_halaman_bundle' => '8 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '100.3.4',
                'no_berkas'            => '100.3.4/3/09/2026',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 100.3.4/3/09/2026 hal Pencegahan Korupsi dan Gratifikasi Idul Fitri 1447 H/2026 M',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Instruksi/Surat Edaran',
                'tanggal_diarsipkan'   => '2026-03-13',
                'jumlah_halaman_bundle' => '1 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
            [
                'kode_klasifikasi'     => '500.12.6',
                'no_berkas'            => '500.12.6/12/V.14/2026',
                'uraian_berkas'        => 'Surat Sekretariat Daerah Provinsi Lampung Nomor 500.12.6/12/V.14/2026 hal Pelatihan Pemanfaatan AI bagi ASN dan Surat Tugas Peserta a.n. GLADHYTA, RIA PRIMADEKA, dan FITRIA WULANDARI tanggal 20 April 2026',
                'kurun_waktu'          => '2026',
                'jumlah_berkas'        => '1 berkas',
                'uraian_arsip'         => 'Tata Kelola E-Government',
                'tanggal_diarsipkan'   => '2026-05-11',
                'jumlah_halaman_bundle' => '7 halaman',
                'lokasi_simpan'        => 'Diarsipkan di Unit Pengolah Kearsipan Bappeda',
                'no_rak'               => '3',
                'no_boks'              => '2',
                'is_biasa'             => true,
                'is_aktif'             => true,
                'is_inaktif'           => false,
            ],
        ];

        foreach ($arsipData as $data) {
            Arsip::create(array_merge($data, [
                'bidang_id' => $sekretariat->id,
                'user_id'   => $opSek->id,
            ]));
        }
    }
}