<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\materi;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $monitoring = Siswa::all(); // Pastikan data diambil dari model
        $materi_monitoring = Materi::all();
        return view('monitoring_siswa.monitoring', compact('monitoring', 'materi_monitoring'));
    }
    

    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        return view('monitoring_siswa.monitoring', compact('siswa'));
    }

    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->update($request->all());
        return redirect()->route('monitoring.index')->with('success', 'Data siswa berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();
        return redirect()->route('monitoring.index')->with('success', 'Data siswa berhasil dihapus.');
    }
}
