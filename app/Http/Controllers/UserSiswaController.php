<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;


class UserSiswaController extends Controller
{
    public function index(){
        $usersiswa = User::where('role','siswa')->get();
        return view('admin.siswa ',compact('usersiswa'));
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
            
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari: admin, siswa, pembimbing',
            'status.required' => 'Status is required',
            'tanggal_mulai.required' =>  'Tanggal mulai is required',
            'tanggal_selesai.required' => 'Tanggal mulai is not a valid date',
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, 
            'role' => $request->role,
            'status' => $request->status,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
    
        ]);

        return redirect()->route('usersiswa.index')->with('success', 'Data berhasil ditambahkan.');
    }
    public function update(Request $request, $id)
    {
        // Validate the incoming request data
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|min:7|max:255',
        ], [
            'username.required' => 'Username is required',
            'username.min' => 'Username must be at least 3 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 7 characters long',
        ]);
    
        // Find the user by ID
        $user = User::findOrFail($id);
    
        try {
            // Update the user's username and password without hashing
            $user->username = $request->username; // Update the username
            $user->password = $request->password; // Store password as plain text
    
            // Save the updated user data
            $user->save();
    
            return redirect()->route('usersiswa.index')->with('success', 'Data berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('usersiswa.index')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
}
