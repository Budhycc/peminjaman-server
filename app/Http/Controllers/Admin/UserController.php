<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('id_pengguna', 'desc')->get();
        return view('admin.user.index', compact('users'));
    }

    public function create()
    {
        return view('admin.user.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'Username' => 'required|string|max:255|unique:users,Username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,user',
            'Unit_Kerja' => 'nullable|string|max:100',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'Username' => 'required|string|max:255|unique:users,Username,'.$id.',id_pengguna',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id.',id_pengguna',
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,user',
            'Unit_Kerja' => 'nullable|string|max:100',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // Prevent demoting the last admin
        if ($user->role === 'admin' && $validated['role'] !== 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Gagal memperbarui. Anda tidak bisa mengubah role admin terakhir.');
            }
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting oneself
        if (auth()->id() == $user->id_pengguna) {
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus. Anda tidak bisa menghapus akun Anda sendiri saat sedang login.');
        }

        // Prevent deleting the last admin
        if ($user->role === 'admin') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus. Ini adalah satu-satunya akun admin yang tersisa.');
            }
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
