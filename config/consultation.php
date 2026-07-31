<?php

return [
    /*
     * Akses pasien tetap dapat dipulihkan ketika session backend berganti
     * atau browser memuat ulang halaman setelah koneksi terputus.
     */
    'patient_access_hours' => (int) env(
        'CONSULTATION_ACCESS_HOURS',
        24
    ),

    'patient_cookie' => env(
        'CONSULTATION_ACCESS_COOKIE',
        'md_farma_patient_access'
    ),

    /*
     * Polling ini hanya menjadi jaring pengaman. WebSocket tetap menjadi
     * jalur realtime utama.
     */
    'sync_interval_ms' => (int) env(
        'CONSULTATION_SYNC_INTERVAL_MS',
        4000
    ),
];
