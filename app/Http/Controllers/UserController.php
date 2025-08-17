<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'username' => 'required|string|max:100|unique:tb_user,username',
            'password' => 'required|string|min:6',
            'email' => 'nullable|email|max:150|unique:tb_user,email',
            'no_telp' => [
                'nullable',
                'string',
                'max:50',
                'unique:tb_user,no_telp',
                'regex:/^(\+?\d{1,3}[-\s]?)?(\(?\d+\)?[\s-]?)*\d+$/'
            ],
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|string|max:50',
            'status_aktif' => 'in:aktif,tidak aktif'
        ]);

        $data = $request->except('password', 'foto_profil');
        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_user', 'public');
        }

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:150',
            'username' => 'required|string|max:100|unique:tb_user,username,' . $id . ',id_user',
            'email' => 'nullable|email|max:150|unique:tb_user,email,' . $id . ',id_user',
            'no_telp' => [
                'nullable',
                'string',
                'max:50',
                'unique:tb_user,no_telp,' . $id . ',id_user',
                'regex:/^(\+?\d{1,3}[-\s]?)?(\(?\d+\)?[\s-]?)*\d+$/'
            ],
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'role' => 'required|string|max:50',
            'status_aktif' => 'in:aktif,tidak aktif'
        ]);

        $data = $request->except('password', 'foto_profil');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil) {
                Storage::disk('public')->delete($user->foto_profil);
            }
            $data['foto_profil'] = $request->file('foto_profil')->store('foto_user', 'public');
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
