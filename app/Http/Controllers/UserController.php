<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class UserController extends Controller
{
    // Tampil semua usAer
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

    $users = $query->latest()->get();
    $totalUser = User::count();
    $userAktif = User::where('status', 'active')->count();

    return view('users.index', compact('users','totalUser','userAktif'));
}

    // Form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required|string',
            'tanggal_gabung' => 'required|date',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => 'active',
            'tanggal_gabung' => $request->tanggal_gabung,
        ]);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User berhasil ditambahkan');
    }

    // Form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|string',
            'status' => 'required|string',
        ]);

        $data = $request->only(['name','email','role','status']);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'min:8|confirmed'
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
                         ->with('success', 'User berhasil diupdate');
    }

    // Hapus user
    public function destroy($user)
{
    User::where('id', $user)->delete();

    return redirect()->route('superadmin.users.index')
                     ->with('success', 'User berhasil dihapus');
}
}