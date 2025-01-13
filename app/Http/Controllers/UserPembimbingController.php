<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserPembimbingController extends Controller
{
    public function index()
    {
        // mengambil data user dengan role pembimbing
        $userpembimbing = User::where('role', 'pembimbing')->get();

        return view('admin.pembimbing', compact('userpembimbing'));
    }

    public function store(Request $request)
    {
        // menyimpan data yang ada pada form tambah pembimbing
        $request->validate([
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:8|max:20',
            'role' => 'required|in:admin,siswa,pembimbing',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'username.required' => 'Username Tidak Boleh Sama.',
            'password.max' => 'Password Tidak Boleh Lebih Dari 20 Karakter.',
            'password.min' => 'Password Tidak Boleh kurang dari 8 karakter',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari:siswa, pembimbing',
            'status.required' => 'Status is required',
        ]);

        // Setelah validasi berhasil, pengguna baru dibuat menggunakan User::create(), dan data dari objek $request dimasukkan ke dalam pengguna baru tersebut.
        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('userpembimbing.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        // mengupdate data yang diubah pada form edit pembimbing
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|min:8|max:20',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username Tidak Boleh Sama.',
            'password.required' => 'Password is required',
            'password.min' => 'Password Tidak Boleh kurang dari 8 karakter.',
            'status.required' => 'Status is required',
        ]);

        // Mencari pengguna berdasarkan ID (User::findOrFail($id)) dan memperbarui field username, password, dan status dengan nilai dari request.
        $user = User::findOrFail($id);

        try {
            $user->username = $request->username;
            $user->password = $request->password;
            $user->status = $request->status;

            $user->save();

            return redirect()->route('userpembimbing.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('userpembimbing.index')->with('error', 'Failed to update user: '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            // Mencari pengguna berdasarkan ID dan menghapus data pengguna tersebut dari database dengan menggunakan $user->delete().
            $user->delete();

            return redirect()->route('userpembimbing.index')->with('success', 'Data berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('userpembimbing.index')->with('error', 'Data gagal dihapus: '.$e->getMessage());
        }
    }
}
