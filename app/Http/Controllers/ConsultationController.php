<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\ConsultationGuest;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    public function create(): View
    {
        return view('consultation.form');
    }

    public function store(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'nama' => [
                'required',
                'string',
                'max:100',
            ],
            'umur' => [
                'required',
                'integer',
                'min:1',
                'max:120',
            ],
            'no_hp' => [
                'required',
                'string',
                'max:25',
                'regex:/^[0-9+\-\s()]+$/',
            ],
            'jenis_konsultasi' => [
                'required',
                'in:resep,non_resep',
            ],
        ]);

        $consultation = DB::transaction(
            function () use ($validated): Consultation {
                $guest = Auth::guard('patient')->user();

                if (
                    ! $guest
                    || ! $guest->expires_at
                    || $guest->expires_at->isPast()
                ) {
                    if ($guest) {
                        Auth::guard('patient')->logout();
                    }

                    $guest = ConsultationGuest::create([
                        'public_id' => (string) Str::uuid(),
                        'expires_at' => now()->addHours(2),
                    ]);

                    Auth::guard('patient')->login($guest);
                } else {
                    $guest->forceFill([
                        'expires_at' => now()->addHours(2),
                    ])->save();
                }

                $consultation = new Consultation([
                    'nama' => $validated['nama'],
                    'umur' => $validated['umur'],
                    'no_hp' => $validated['no_hp'],
                    'jenis_konsultasi' =>
                        $validated['jenis_konsultasi'],
                    'status' => 'aktif',
                ]);

                $consultation->guest()->associate($guest);
                $consultation->save();

                return $consultation;
            }
        );

        $request->session()->regenerate();

        return redirect()->route(
            'chat.show',
            $consultation
        );
    }
}
