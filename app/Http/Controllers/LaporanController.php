<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use App\Models\SuratKeluar;
use App\Models\Bidang;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bidangs = Bidang::orderBy('nama_bidang')->get();
        $data = $this->getFilteredReportData($request);

        return view('laporan.index', array_merge($data, [
            'bidangs' => $bidangs
        ]));
    }

    public function print(Request $request)
    {
        $data = $this->getFilteredReportData($request);
        return view('laporan.print', $data);
    }

    private function getFilteredReportData(Request $request)
    {
        $type = $request->get('jenis_laporan', 'semua');
        $bidangId = $request->get('bidang_id');
        $dari = $request->get('tanggal_dari');
        $sampai = $request->get('tanggal_sampai');

        $suratMasuk = collect();
        $suratKeluar = collect();

        // Query Surat Masuk
        if ($type === 'semua' || $type === 'masuk') {
            $query = SuratMasuk::with(['bidang', 'boks']);
            if ($bidangId) {
                $query->where('bidang_id', $bidangId);
            }
            if ($dari) {
                $query->whereDate('tanggal_surat', '>=', $dari);
            }
            if ($sampai) {
                $query->whereDate('tanggal_surat', '<=', $sampai);
            }
            $suratMasuk = $query->latest()->get();
        }

        // Query Surat Keluar
        if ($type === 'semua' || $type === 'keluar') {
            $query = SuratKeluar::with(['bidang', 'boks']);
            if ($bidangId) {
                $query->where('bidang_id', $bidangId);
            }
            if ($dari) {
                $query->whereDate('tanggal_surat', '>=', $dari);
            }
            if ($sampai) {
                $query->whereDate('tanggal_surat', '<=', $sampai);
            }
            $suratKeluar = $query->latest()->get();
        }

        // Merge and sort them
        $items = collect();
        foreach ($suratMasuk as $sm) {
            $items->push([
                'id' => $sm->id,
                'jenis' => 'Surat Masuk',
                'nomor_surat' => $sm->nomor_surat,
                'tanggal_surat' => $sm->tanggal_surat,
                'perihal' => $sm->perihal,
                'asal_tujuan' => $sm->pengirim,
                'boks' => $sm->boks->nomor_boks . ' (' . $sm->boks->lokasi_rak . ')',
                'bidang' => $sm->bidang->nama_bidang,
            ]);
        }

        foreach ($suratKeluar as $sk) {
            $items->push([
                'id' => $sk->id,
                'jenis' => 'Surat Keluar',
                'nomor_surat' => $sk->nomor_surat,
                'tanggal_surat' => $sk->tanggal_surat,
                'perihal' => $sk->perihal,
                'asal_tujuan' => $sk->tujuan,
                'boks' => $sk->boks->nomor_boks . ' (' . $sk->boks->lokasi_rak . ')',
                'bidang' => $sk->bidang->nama_bidang,
            ]);
        }

        $items = $items->sortByDesc('tanggal_surat')->values();

        $selectedBidang = $bidangId ? Bidang::find($bidangId) : null;

        return [
            'items' => $items,
            'jenis_laporan' => $type,
            'tanggal_dari' => $dari,
            'tanggal_sampai' => $sampai,
            'bidang_id' => $bidangId,
            'selectedBidang' => $selectedBidang,
        ];
    }
}
