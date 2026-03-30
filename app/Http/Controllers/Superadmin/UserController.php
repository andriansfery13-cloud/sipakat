<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Kecamatan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        // Hanya menampilkan user dengan role admin_kecamatan
        $users = User::where('role', 'admin_kecamatan')->with('kecamatan')->latest()->get();
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('superadmin.users.create', compact('kecamatans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin_kecamatan',
            'kecamatan_id' => $validated['kecamatan_id'],
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Admin Kecamatan berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $kecamatans = Kecamatan::orderBy('nama_kecamatan')->get();
        return view('superadmin.users.edit', compact('user', 'kecamatans'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'kecamatan_id' => 'required|exists:kecamatans,id',
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'kecamatan_id' => $validated['kecamatan_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Data Admin Kecamatan berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('superadmin.users.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Admin Kecamatan berhasil dihapus.');
    }
}
