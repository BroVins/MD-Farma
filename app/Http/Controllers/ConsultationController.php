<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsultationController extends Controller
{
    public function create(): View
    {
        return view('consultation.form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'umur' => ['required', 'integer', 'min:1', 'max:120'],
            'no_hp' => ['required', 'string', 'max:20'],
            'jenis_konsultasi' => ['required', 'in:resep,non_resep'],
        ]);

        $consultation = Consultation::create([
            ...$validated,
            'status' => 'aktif',
        ]);

        return redirect()->route('chat.show', $consultation->id);
    }
}
