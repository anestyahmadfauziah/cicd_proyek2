<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Authenticatable;

final class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
{
    $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ], [
        'username.required' => 'Username tidak boleh kosong!',
        'password.required' => 'Password tidak boleh kosong!',
    ]);

    Auth::guard('superadmin')->logout();
    Auth::guard('web')->logout();

    // ================= SUPERADMIN =================
    $superadminCredentials = [
        'username' => $request->username,
        'password' => $request->password,
    ];

    if (Auth::guard('superadmin')->attempt($superadminCredentials)) {
        $request->session()->regenerate();

        return redirect()->route('superadmin.dashboard')
            ->with('success', 'Login berhasil sebagai Super Admin!');
    }

    // ================= ADMIN =================
    $adminCredentials = [
        'username' => $request->username,
        'password' => $request->password,
    ];

    if (Auth::guard('web')->attempt($adminCredentials)) {
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Login berhasil sebagai Admin!');
    }

    return back()
        ->withInput()
        ->with('error', 'Username atau password salah!');

}

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('superadmin')->logout();
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Berhasil logout!');
    }

    public function showChangePassword(): RedirectResponse
    {
        if (Auth::guard('superadmin')->check()) {
            return redirect()->route('superadmin.dashboard');
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect('/login');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => [
                'required',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'
            ],
        ]);

        $guard = $this->getActiveGuard();

        if (!$guard) {
            return redirect('/login')
                ->with('error', 'Session tidak valid, silakan login ulang.');
        }

        /** @var Authenticatable|null $user */
        $user = Auth::guard($guard)->user();

        if (!$user) {
            return redirect('/login')
                ->with('error', 'User tidak ditemukan.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route(
            $guard === 'web' ? 'admin.dashboard' : 'superadmin.dashboard'
        )->with('success', 'Password berhasil diubah!');
    }

    private function getActiveGuard(): ?string
    {
        if (Auth::guard('superadmin')->check()) {
            return 'superadmin';
        }

        if (Auth::guard('web')->check()) {
            return 'web';
        } 

        return null;
    }
}
