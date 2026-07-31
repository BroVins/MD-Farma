<?php

return [
    /*
     * Satu consultation_guest mewakili satu browser/perangkat tepercaya.
     * Token mentah hanya berada di cookie; database menyimpan hash-nya.
     */
    'patient_device_days' => (int) env(
        'CONSULTATION_DEVICE_DAYS',
        90
    ),

    /*
     * Masa perangkat hanya diperpanjang ketika sudah mendekati kedaluwarsa.
     * Polling pesan tidak dianggap sebagai aktivitas yang memperpanjang akses.
     */
    'patient_device_refresh_window_days' => (int) env(
        'CONSULTATION_DEVICE_REFRESH_WINDOW_DAYS',
        30
    ),

    /*
     * last_seen_at paling sering ditulis sekali dalam rentang ini agar request
     * berulang tidak menghasilkan penulisan database berlebihan.
     */
    'patient_device_seen_interval_hours' => (int) env(
        'CONSULTATION_DEVICE_SEEN_INTERVAL_HOURS',
        6
    ),

    'patient_cookie' => env(
        'CONSULTATION_ACCESS_COOKIE',
        'md_farma_patient_access'
    ),

    /*
     * Password Riwayat hanya disimpan sebagai hash. Setelah berhasil
     * diverifikasi, riwayat terbuka untuk sesi terbatas pada browser itu.
     */
    'history_password_min_length' => (int) env(
        'CONSULTATION_HISTORY_PASSWORD_MIN_LENGTH',
        10
    ),

    'history_password_max_attempts' => (int) env(
        'CONSULTATION_HISTORY_PASSWORD_MAX_ATTEMPTS',
        5
    ),

    'history_password_lock_minutes' => (int) env(
        'CONSULTATION_HISTORY_PASSWORD_LOCK_MINUTES',
        15
    ),

    'history_unlock_minutes' => (int) env(
        'CONSULTATION_HISTORY_UNLOCK_MINUTES',
        480
    ),

    /*
     * Isi chat konsultasi selesai dapat dibuka pasien selama periode ini.
     * Setelah lewat, data tetap tersimpan untuk arsip internal admin tetapi
     * tidak lagi tersedia melalui dashboard atau endpoint pasien.
     */
    'patient_history_days' => (int) env(
        'CONSULTATION_PATIENT_HISTORY_DAYS',
        60
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
