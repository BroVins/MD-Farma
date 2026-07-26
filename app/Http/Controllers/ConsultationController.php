<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Consultation;

class ConsultationController extends Controller
{
    public function create()
    {
        return view('consultation.form');
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'umur' => 'required|integer',
            'no_hp' => 'required',
            'jenis_konsultasi' => 'required'
        ]);


        $consultation = Consultation::create([
            'nama' => $request->nama,
            'umur' => $request->umur,
            'no_hp' => $request->no_hp,
            'jenis_konsultasi' => $request->jenis_konsultasi,
            'status' => 'aktif'
        ]);


        return redirect('/chat/'.$consultation->id);
    }
}