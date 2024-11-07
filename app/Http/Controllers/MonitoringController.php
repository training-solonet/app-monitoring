<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Siswa;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $siswa = Siswa::all();
        return view('monitoring_siswa.monitoring', compact('siswa'));
    }
}
