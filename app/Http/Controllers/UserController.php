<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = strtolower($request->search);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(name) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(role) LIKE ?', ["%{$search}%"]);
            });
        }

        $users     = $query->latest()->get();
        $totalUser = User::count();
        $userAktif = User::where('status', 'active')->count();

        return view('users.index', compact('users', 'totalUser', 'userAktif'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username', // ✅ tambah username
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            // ✅ role tidak perlu divalidasi dari request
        ]);

        User::create([
            'name'     => $request->name,
            'username' => $request->username, // ✅ tambah username
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'admin', // ✅ hardcode admin
            'status'   => 'active',
        ]);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User berhasil ditambahkan');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'role'   => 'required|in:admin,user', // ✅ hapus superadmin
            'status' => 'required|in:active,inactive',
        ]);

        $data = $request->only(['name', 'email', 'role', 'status']);

        if ($request->filled('password')) {
            $request->validate(['password' => 'min:8|confirmed']);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (auth('superadmin')->id() === $user->id) {
            return redirect()->route('superadmin.users.index')
                             ->with('error', 'Tidak bisa menghapus akun sendiri!');
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User berhasil dihapus');
    }
}