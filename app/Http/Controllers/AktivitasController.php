<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aktivitas;

class AktivitasController extends Controller
{
    public function index() {
        $aktivitas = Aktivitas::all();
        return view('pembimbing.aktivitas', compact('aktivitas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_aktivitas' => 'required|string|max:255',
        ]);

        Aktivitas::create([
            'nama_aktivitas' => $request->input('nama_aktivitas'),
        ]);

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil ditambahkan.');
    }
    public function edit($id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        return view('pembimbing.edit_aktivitas', compact('aktivitas'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aktivitas' => 'required|string|max:255',
        ]);

        $aktivitas = Aktivitas::findOrFail($id);
        $aktivitas->update([
            'nama_aktivitas' => $request->input('nama_aktivitas'),
        ]);

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        $aktivitas->delete();

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil dihapus.');
    }
}
