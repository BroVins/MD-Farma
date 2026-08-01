<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp resmi Apotek MD Farma
    |--------------------------------------------------------------------------
    |
    | Gunakan format internasional tanpa tanda +, spasi, atau tanda hubung.
    | Contoh Indonesia: 6281234567890
    |
    */
    'whatsapp_number' => env('MD_FARMA_WHATSAPP_NUMBER', ''),

    'whatsapp_message' => env(
        'MD_FARMA_WHATSAPP_MESSAGE',
        'Halo Apotek MD Farma, saya ingin menanyakan informasi kerja sama.'
    ),

    /*
    |--------------------------------------------------------------------------
    | Persetujuan privasi konsultasi
    |--------------------------------------------------------------------------
    |
    | Versi dan teks ini disimpan sebagai snapshot pada setiap konsultasi baru
    | agar persetujuan dapat ditelusuri walaupun kebijakan berubah kemudian.
    |
    */
    'privacy_policy_version' => env(
        'MD_FARMA_PRIVACY_POLICY_VERSION',
        '2026-08-01'
    ),

    'privacy_consent_text' => env(
        'MD_FARMA_PRIVACY_CONSENT_TEXT',
        'Saya menyetujui MD Farma memproses data identitas, isi percakapan, dan lampiran untuk pelayanan konsultasi kefarmasian, dokumentasi pelayanan, keamanan, audit, serta pemenuhan kewajiban yang berlaku. Saya memahami isi chat dapat diakses melalui dashboard pasien selama 60 hari setelah konsultasi selesai, kemudian tidak lagi ditampilkan kepada pasien tetapi tetap dikelola sebagai arsip internal sesuai kebijakan retensi MD Farma.'
    ),

];
