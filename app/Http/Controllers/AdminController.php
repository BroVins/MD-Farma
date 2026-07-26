<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function login(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('admin_id')) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $admin = Admin::where('username', $validated['username'])->first();

        if (! $admin || ! Hash::check($validated['password'], $admin->password)) {
            return back()
                ->withInput($request->only('username'))
                ->with('error', 'Username atau password salah.');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'admin_id' => $admin->id,
            'admin_username' => $admin->username,
        ]);

        return redirect()->route('admin.dashboard');
    }

    public function dashboard(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('admin_id')) {
            return redirect()
                ->route('admin.login')
                ->with('error', 'Silakan login sebagai admin terlebih dahulu.');
        }

        $totalConsultation = Consultation::count();
        $activeChat = Consultation::where('status', 'aktif')->count();
        $resep = Consultation::where('jenis_konsultasi', 'resep')->count();
        $nonResep = Consultation::where('jenis_konsultasi', 'non_resep')->count();
        $consultations = Consultation::latest()->get();

        return view('admin.dashboard', compact(
            'totalConsultation',
            'activeChat',
            'resep',
            'nonResep',
            'consultations'
        ));
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda berhasil logout.');
    }
}
