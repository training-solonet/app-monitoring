<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jurusan;

class UserAdminController extends Controller
{
    public function index(){
        $user = User::where('role','admin')->get();
        return view('admin.add',compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:7|max:255',
            'role' => 'required|in:admin,siswa,pembimbing',
            'status' => 'required|in:Aktif,Tidak Aktif',

        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari: admin, siswa, pembimbing',
            'status.required' => 'Status is required',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, 
            'role' => $request->role,
            'status' => $request->status,
    
        ]);

        return redirect()->route('useradmin.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|min:7|max:255',
            'status' => 'required|in:Aktif,Tidak Aktif',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username must be at least 3 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 7 characters long',
            'status.required' => 'Status is required',
        ]);
    
        $user = User::findOrFail($id);
    
        try {
            $user->username = $request->username;             $user->password = $request->password;
            $user->status  = $request->status;
    
            $user->save();
    
            return redirect()->route('useradmin.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('useradmin.index')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
    
    
}
