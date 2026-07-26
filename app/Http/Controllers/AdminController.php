<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(): View|RedirectResponse
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => [
                'required',
                'string',
            ],
            'password' => [
                'required',
                'string',
            ],
        ]);

        if (! Auth::guard('admin')->attempt($credentials)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        $request->session()->regenerate();

        return redirect()->intended(
            route('admin.dashboard')
        );
    }

    public function dashboard(): View
    {
        $totalConsultation = Consultation::count();

        $activeChat = Consultation::where(
            'status',
            'aktif'
        )->count();

        $resep = Consultation::where(
            'jenis_konsultasi',
            'resep'
        )->count();

        $nonResep = Consultation::where(
            'jenis_konsultasi',
            'non_resep'
        )->count();

        $consultations = Consultation::latest()->get();

        return view(
            'admin.dashboard',
            compact(
                'totalConsultation',
                'activeChat',
                'resep',
                'nonResep',
                'consultations'
            )
        );
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }
}
