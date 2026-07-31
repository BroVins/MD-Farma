<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\Consultation;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(
        Request $request,
        Consultation $consultation
    ): View {
        $consultation->load([
            'messages' => fn ($query) =>
                $query->with('classificationNotice')->oldest(),
        ]);

        AnalyticsEvent::recordOnce(
            $request,
            'chat_opened',
            $consultation,
            ['actor' => 'patient']
        );

        $patientName =
            trim((string) ($consultation->nama ?? '')) ?: 'Pasien';
        $consultationLabel =
            $consultation->jenis_konsultasi === 'resep'
                ? 'Resep Dokter'
                : 'Non Resep';
        $timezone = config(
            'analytics.timezone',
            'Asia/Jakarta'
        );
        $started = $consultation->created_at
            ->copy()
            ->timezone($timezone);
        $startedDateKey = $started->format('Y-m-d');

        return view(
            'consultation.chat',
            [
                'consultation' => $consultation,
                'isAdminView' => false,
                'patientName' => $patientName,
                'consultationLabel' => $consultationLabel,
                'timezone' => $timezone,
                'started' => $started,
                'startedDateKey' => $startedDateKey,
                'lastDate' => $startedDateKey,
                'patientHistoryAvailableUntil' =>
                    $consultation->patientHistoryAvailableUntil(),
            ]
        );
    }
}
