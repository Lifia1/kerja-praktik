<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Display the dynamic dashboard based on user role.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            // Fetch stats for Admin dashboard
            $stats = [
                'total_users' => User::count(),
                'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
                'total_dosen' => User::where('role', 'dosen')->count(),
                'total_admin' => User::where('role', 'admin')->count(),
            ];

            $recentUsers = User::latest()->limit(5)->get();

            return view('dashboard', compact('stats', 'recentUsers'));
        }

        if ($user->isDosen()) {
            // Stats for Dosen dashboard
            $stats = [
                'mahasiswa_bimbingan' => 8,
                'logbook_pending' => 5,
                'laporan_review' => 2,
            ];

            $bimbinganList = [
                ['name' => 'Adi Wijaya', 'nim' => '2201010045', 'perusahaan' => 'PT Telkom Indonesia', 'status' => 'Aktif'],
                ['name' => 'Rian Hidayat', 'nim' => '2201010087', 'perusahaan' => 'GoTo Group', 'status' => 'Aktif'],
                ['name' => 'Siti Aminah', 'nim' => '2201010101', 'perusahaan' => 'PT Bank Mandiri', 'status' => 'Review Laporan'],
            ];

            return view('dashboard', compact('stats', 'bimbinganList'));
        }

        // Default: Mahasiswa dashboard
        $stats = [
            'status' => 'Aktif (Sedang Berjalan)',
            'perusahaan' => 'PT Telkom Indonesia',
            'pembimbing' => 'Dr. Budi Santoso',
            'logbook_count' => 12,
            'progress' => 60,
        ];

        $recentLogbooks = [
            ['tanggal' => '2026-06-22', 'kegiatan' => 'Melakukan integrasi API middleware untuk multi-role user.', 'status' => 'Disetujui'],
            ['tanggal' => '2026-06-21', 'kegiatan' => 'Mendesain arsitektur database dan model tabel user.', 'status' => 'Disetujui'],
            ['tanggal' => '2026-06-20', 'kegiatan' => 'Mempelajari struktur folder framework Laravel 11/12.', 'status' => 'Disetujui'],
        ];

        return view('dashboard', compact('stats', 'recentLogbooks'));
    }
}
