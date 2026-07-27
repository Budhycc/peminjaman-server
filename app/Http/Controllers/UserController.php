<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pengguna' => 'required|string|max:100',
            'Username' => 'required|string|max:50|unique:users',
            'password' => 'required|string|min:6',
            'email' => 'required|string|email|max:100|unique:users',
            'role' => 'required|in:admin,user'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_pengguna' => 'sometimes|string|max:100',
            'Username' => 'sometimes|string|max:50|unique:users,Username,' . $user->id_pengguna . ',id_pengguna',
            'password' => 'sometimes|string|min:6',
            'email' => 'sometimes|string|email|max:100|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'role' => 'sometimes|in:admin,user'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
