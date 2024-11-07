<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class UserSiswaController extends Controller
{
    public function index() {
        $usersToUpdate = User::where('role', 'siswa')
                             ->where('status', 'Aktif')
                             ->where('tanggal_selesai', '<', Carbon::now())
                             ->get();

        foreach ($usersToUpdate as $user) {
            $user->status = 'Tidak Aktif';
            $user->save();
        }

        $usersiswa = User::where('role', 'siswa')->get();
    
        return view('admin.siswa', compact('usersiswa'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:7|max:255',
            'role' => 'required|in:admin,siswa,pembimbing',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jurusan' => 'required|in:RPL,TKJ',
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari: admin, siswa, pembimbing',
            'status.required' => 'Status is required',
            'tanggal_mulai.required' => 'Tanggal mulai is required',
            'tanggal_selesai.required' => 'Tanggal selesai is required',
            'jurusan.required' => 'jurusan is required',
        ]);

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        $masaPkl = round($tanggalMulai->diffInDays($tanggalSelesai) / 30);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, 
            'role' => $request->role,
            'status' => $request->status,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'masa_pkl' => $masaPkl,
            'jurusan' => $request->jurusan,
        ]);

        return redirect()->route('usersiswa.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|min:7|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jurusan' => 'required|in:RPL,TKJ',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username must be at least 3 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 7 characters long',
            'tanggal_mulai.required' => 'Tanggal mulai is required',
            'tanggal_selesai.required' => 'Tanggal selesai is required',
            'jurusan.required' => 'jurusan is required',
        ]);

        $user = User::findOrFail($id);

        try {
            $user->username = $request->username;
            $user->password = $request->password;
            $user->tanggal_mulai = $request->tanggal_mulai;
            $user->tanggal_selesai = $request->tanggal_selesai;
            $user->jurusan = $request->jurusan;

            $tanggalMulai = Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $user->masa_pkl = round($tanggalMulai->diffInDays($tanggalSelesai) / 30);

            $user->save();

            return redirect()->route('usersiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('usersiswa.index')->with('error', 'Data Gagal Diupdate' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('usersiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('usersiswa.index')->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }
}
