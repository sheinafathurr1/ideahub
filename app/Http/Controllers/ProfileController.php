<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    // Menampilkan halaman form edit profil
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    // Memproses update data profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi Input
        $rules = [
            'name' => 'required|string|max:255',
            // Pengecualian unique email untuk user yang sedang login
            'email' => 'required|email|unique:users,email,' . $user->id,
            'university_name' => 'required|string|max:255',
            'university_type' => 'required|in:PTN,PTS',
            'university_category' => 'required|string',
            'has_disability_study_program' => 'required|boolean',
            'phone_number' => 'required|numeric',
            'university_logo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];

        // Validasi password HANYA JIKA user mengisi field password
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validated = $request->validate($rules);

        // 2. Update Data User
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->university_name = $validated['university_name'];
        $user->university_type = $validated['university_type'];
        $user->university_category = $validated['university_category'];
        $user->has_disability_study_program = $validated['has_disability_study_program'];
        $user->phone_number = $validated['phone_number'];

        // 3. Jika password diisi, update password
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        // 4. Jika ada upload logo baru
        if ($request->hasFile('university_logo')) {
            // Hapus logo lama jika ada
            if ($user->university_logo) {
                Storage::disk('public')->delete($user->university_logo);
            }

            $file = $request->file('university_logo');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'Logo_' . Str::slug($validated['university_name']) . '_' . time() . '.' . $extension;
            
            $user->university_logo = $file->storeAs('university_logos', $fileName, 'public');
        }

        // Simpan perubahan
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }
}