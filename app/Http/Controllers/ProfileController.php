<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('account-pages.profile');
    }

    public function uploadPfp(Request $request)
    {
        // Validasi request
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif,jfif|max:2048'
        ]);

        try {
            // Dapatkan user yang sedang login
            $user = User::find(Auth::id());

            if (!Storage::disk('public')->exists('pfp')) {
                Storage::disk('public')->makeDirectory('pfp');
            }
            
            // Generate nama file yang unik
            $imagePath = null;
            if($request->hasFile('profile_image')){
                if ($user->pfp_path && Storage::disk('public')->exists($user->pfp_path)) {
                    Storage::disk('public')->delete($user->pfp_path);
                }

                // Dapatkan file dari request
                $image = $request->file('profile_image');
                $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $imagePath = $image->storeAs('pfp', $imageName, 'public');
            }

            if($imagePath !== null){
                $user->update(['pfp_path' => $imagePath]);
            }
            
            return redirect()->back()->with('success', 'Foto profil berhasil diubah!');
            
        } catch (\Exception $e) {
            return redirect()->back()->withErrors('Foto gagal diubah!');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try{
            $user = User::find(Auth::id());

            $request->validate([
                'nama_lengkap' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username,'.$user->id,
                'no_hp' => 'nullable|string|max:20',
            ]);

            $user->update([
                'nama_lengkap' => $request->nama_lengkap,
                'username' => $request->username,
                'no_hp' => $request->no_hp,
            ]);

            return redirect()->back()->with('success', 'Profil berhasil diperbarui.');
        }
        catch(\Exception $e){
            return redirect()->back()->withErrors('Profil gagal diperbarui.');
        }
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = User::find(Auth::id());

        // cek password lama
        if ($request->current_password !== $user->password) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        } else if($request->new_password === $user->password){
            return back()->withErrors('Password tidak boleh sama.');
        }

        // update password
        $user->update([
            'password' => $request->new_password,
        ]);

        return back()->with('success', 'Password berhasil diubah.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
