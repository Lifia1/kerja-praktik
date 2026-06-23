<?php

namespace App\Http\Controllers;

use App\Models\Boks;
use Illuminate\Http\Request;

class BoksController extends Controller
{
    public function index()
    {
        $boks = Boks::latest()->paginate(10);
        return view('boks.index', compact('boks'));
    }

    public function create()
    {
        return view('boks.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_boks' => ['required', 'string', 'max:255', 'unique:boks'],
            'lokasi_rak' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        Boks::create($request->all());

        return redirect()->route('boks.index')->with('success', 'Boks berhasil ditambahkan.');
    }

    public function show(Boks $bok)
    {
        return redirect()->route('boks.index');
    }

    public function edit(Boks $bok)
    {
        return view('boks.edit', compact('bok'));
    }

    public function update(Request $request, Boks $bok)
    {
        $request->validate([
            'nomor_boks' => ['required', 'string', 'max:255', 'unique:boks,nomor_boks,'.$bok->id],
            'lokasi_rak' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string'],
        ]);

        $bok->update($request->all());

        return redirect()->route('boks.index')->with('success', 'Boks berhasil diperbarui.');
    }

    public function destroy(Boks $bok)
    {
        $bok->delete();
        return redirect()->route('boks.index')->with('success', 'Boks berhasil dihapus.');
    }
}
