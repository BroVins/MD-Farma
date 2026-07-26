<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }


    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);


        $admin = Admin::where(
            'username',
            $request->username
        )->first();


        if ($admin && Hash::check(
            $request->password,
            $admin->password
        )) {

            session([
                'admin_id' => $admin->id,
                'admin_username' => $admin->username
            ]);


            return redirect('/admin/dashboard');
        }


        return back()->with(
            'error',
            'Username atau password salah'
        );
    }


    public function dashboard()
{
    $totalConsultation = \App\Models\Consultation::count();

    $activeChat = \App\Models\Consultation::where(
        'status',
        'aktif'
    )->count();


    $resep = \App\Models\Consultation::where(
        'jenis_konsultasi',
        'resep'
    )->count();


    $nonResep = \App\Models\Consultation::where(
        'jenis_konsultasi',
        'non_resep'
    )->count();


    $consultations = \App\Models\Consultation::latest()->get();


    return view('admin.dashboard', compact(
        'totalConsultation',
        'activeChat',
        'resep',
        'nonResep',
        'consultations'
    ));
}


    public function logout()
    {
        session()->flush();

        return redirect('/admin/login');
    }
}