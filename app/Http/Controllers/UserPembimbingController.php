<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserPembimbingController extends Controller
{
    public function index(){
        $userpembimbing = User::where('role','pembimbing')->get();
        return view('admin.pembimbing',compact('userpembimbing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255|unique:users',
            'password' => 'required|min:7|max:255',
            'role' => 'required|in:admin,siswa,pembimbing',
            'status' => 'required|in:Aktif,Tidak Aktif'
        ], [
            'username.required' => 'Username is required',
            'password.required' => 'Password is required',
            'role.required' => 'Role is required',
            'role.in' => 'Role harus salah satu dari: admin, siswa, pembimbing',
            'status.required' => 'Status is required'
        ]);

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password, 
            'role' => $request->role,
            'status' => $request->status,
        ]);

        return redirect()->route('userpembimbing.index')->with('success', 'User berhasil ditambahkan.');
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
    
            return redirect()->route('userpembimbing.index')->with('success', 'User berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->route('userpembimbing.index')->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }
    
    
}
