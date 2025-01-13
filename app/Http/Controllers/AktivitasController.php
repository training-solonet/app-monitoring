<?php

namespace App\Http\Controllers;

use App\Models\Aktivitas;
use Illuminate\Http\Request;

class AktivitasController extends Controller
{
    public function index()
    {
        // Mengambil semua data dari model Aktivitas.
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
        // Mencari data Aktivitas berdasarkan id yang diberikan.
        $aktivitas = Aktivitas::findOrFail($id);

        return view('pembimbing.edit_aktivitas', compact('aktivitas'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_aktivitas' => 'required|string|max:255',
        ]);

        // Mencari data aktivitas berdasarkan id.
        $aktivitas = Aktivitas::findOrFail($id);
        // Memperbarui kolom nama_aktivitas dengan input baru dari pengguna.
        $aktivitas->update([
            'nama_aktivitas' => $request->input('nama_aktivitas'),
        ]);

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $aktivitas = Aktivitas::findOrFail($id);
        // Menghapus data tersebut dari database.
        $aktivitas->delete();

        return redirect()->route('aktivitas.index')->with('success', 'Aktivitas berhasil dihapus.');
    }
}
