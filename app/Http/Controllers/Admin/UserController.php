<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected array $roles = ['admin', 'staff', 'kepala_unit', 'kasubbag', 'pemohon'];

    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = $this->roles;
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'nik' => 'nullable|numeric|unique:users,nik',
            'no_hp' => 'nullable|string|max:50',
            'role' => 'required|in:admin,staff,kepala_unit,kasubbag,pemohon',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nik' => $validated['nik'] ?? null,
            'no_hp' => $validated['no_hp'] ?? null,
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = $this->roles;
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'nik' => 'nullable|numeric|unique:users,nik,' . $user->id,
            'no_hp' => 'nullable|string|max:50',
            'role' => 'required|in:admin,staff,kepala_unit,kasubbag,pemohon',
        ]);

        // Larang admin mengubah rolenya sendiri
        if (Auth::id() === $user->id && $validated['role'] !== $user->role) {
            return back()->with('error', 'Anda tidak dapat mengubah peran (role) akun Anda sendiri.')->withInput();
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        // Cegah hapus dirinya sendiri
        if (Auth::id() === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        // Larang hapus akun ber-role admin sama sekali
        if ($user->role === 'admin') {
            return back()->with('error', 'Akun dengan role admin tidak dapat dihapus.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun pengguna berhasil dihapus.');
    }

    public function showResetForm(User $user)
    {
        return view('admin.users.reset-password', compact('user'));
    }

    public function resetPassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => 'required|min:6|confirmed',
        ], [
            'password.min' => 'Password baru setidaknya harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama dengan password baru.',
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Password pengguna berhasil direset.');
    }
}
