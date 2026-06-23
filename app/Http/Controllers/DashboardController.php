<?php

namespace App\Http\Controllers;

use App\Models\Bidang;
use App\Models\Boks;
use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $totalBidang = Bidang::count();
            $totalBoks = Boks::count();
            $totalSuratMasuk = SuratMasuk::count();
            $totalSuratKeluar = SuratKeluar::count();

            $recentSuratMasuk = SuratMasuk::with(['bidang', 'boks'])->latest()->take(5)->get();
            $recentSuratKeluar = SuratKeluar::with(['bidang', 'boks'])->latest()->take(5)->get();
        } else {
            $totalBidang = 1; // Operator is in 1 bidang
            $totalBoks = Boks::count(); // All boks exist physically
            $totalSuratMasuk = SuratMasuk::where('bidang_id', $user->bidang_id)->count();
            $totalSuratKeluar = SuratKeluar::where('bidang_id', $user->bidang_id)->count();

            $recentSuratMasuk = SuratMasuk::with(['bidang', 'boks'])
                ->where('bidang_id', $user->bidang_id)
                ->latest()
                ->take(5)
                ->get();
            $recentSuratKeluar = SuratKeluar::with(['bidang', 'boks'])
                ->where('bidang_id', $user->bidang_id)
                ->latest()
                ->take(5)
                ->get();
        }

        return view('dashboard', compact(
            'totalBidang',
            'totalBoks',
            'totalSuratMasuk',
            'totalSuratKeluar',
            'recentSuratMasuk',
            'recentSuratKeluar'
        ));
    }
}
