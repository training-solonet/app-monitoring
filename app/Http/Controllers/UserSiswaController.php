<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class UserSiswaController extends Controller
{
    public function index()
    {
        // berisi koleksi pengguna yang memiliki peran "siswa", status "Aktif", dan tanggal selesai (tanggal_selesai) yang lebih kecil dari tanggal saat ini
        $usersToUpdate = User::where('role', 'siswa')
            ->where('status', 'Aktif')
            ->where('tanggal_selesai', '<', Carbon::now())
            ->get();

        foreach ($usersToUpdate as $user) {
            $user->status = 'Tidak Aktif';
            $user->save();
        }

        // mengambil data user sesuai dengan rolenya siswa
        $usersiswa = User::where('role', 'siswa')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.siswa', compact('usersiswa'));
    }

    public function store(Request $request)
    {
        // menyimpan data baru siswa yang telah di input pada form tambah siswa
        $request->validate([
            'nama_lengkap' => 'required|string',
            'nickname' => 'required|string',
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:8|max:20',
            'role' => 'required|in:siswa,pembimbing',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jurusan' => 'required|in:RPL,TKJ,DKV',
            'no_hp' => 'nullable|regex:/^62[0-9]{9,13}$/',
        ], [
            'username.required' => 'Username Tidak Boleh Sama.',
            'password.min' => 'Password tidak boleh kurang dari 8 karakter',
            'password.max' => 'Password tidak boleh lebih dari 20 karakter',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari siswa atau pembimbing',
            'status.required' => 'Status is required',
            'tanggal_mulai.required' => 'Tanggal mulai is required',
            'tanggal_selesai.required' => 'Tanggal selesai is required',
            'jurusan.required' => 'jurusan is required',
        ]);

        $tanggalMulai = Carbon::parse($request->tanggal_mulai);
        $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
        // Sama seperti pada method store, menghitung durasi masa PKL dengan cara yang sama, berdasarkan tanggal mulai dan tanggal selesai yang baru.
        $masaPkl = round($tanggalMulai->diffInDays($tanggalSelesai) / 30);

        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'nickname' => $request->nickname,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'masa_pkl' => $masaPkl,
            'jurusan' => $request->jurusan,
            'no_hp' => $request->no_hp
        ]);

        return redirect()->route('usersiswa.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // mengupdate data siswa yang telah di input pada form edit siswa
        $request->validate([
            'nama_lengkap' => 'required|string',
            'nickname' =>'required|string',
            'username' => 'required|max:255',
            'password' => 'required|min:8|max:20',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date',
            'jurusan' => 'required|in:RPL,TKJ,DKV',
            'status' => 'required|in:Aktif,Tidak Aktif',
            'no_hp' => 'nullable|regex:/^62[0-9]{9,13}$/',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username tidak boleh sama',
            'password.required' => 'Password is required',
            'password.min' => 'Password tidak boleh kurang dari 8 karakter',
            'tanggal_mulai.required' => 'Tanggal mulai is required',
            'tanggal_selesai.required' => 'Tanggal selesai is required',
            'jurusan.required' => 'jurusan is required',
            'status.required' => 'status is required',
        ]);

        // Menyimpan instance dari model User yang ditemukan dengan ID yang diberikan melalui parameter $id. Jika tidak ada pengguna dengan ID tersebut, akan menghasilkan error 404.
        $user = User::findOrFail($id);

        try {
            $user->nama_lengkap = $request->nama_lengkap;
            $user->nickname = $request->nickname;
            $user->username = $request->username;
            $user->password = $request->password;
            $user->tanggal_mulai = $request->tanggal_mulai;
            $user->tanggal_selesai = $request->tanggal_selesai;
            $user->jurusan = $request->jurusan;
            $user->status = $request->status;
            $user->no_hp = $request->no_hp;

            $tanggalMulai = Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = Carbon::parse($request->tanggal_selesai);
            $user->masa_pkl = round($tanggalMulai->diffInDays($tanggalSelesai) / 30);

            $user->save();

            return redirect()->route('usersiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('usersiswa.index')->with('error', 'Data Gagal Diupdate'.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return redirect()->route('usersiswa.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('usersiswa.index')->with('error', 'Data gagal dihapus: '.$e->getMessage());
        }
    }
}
