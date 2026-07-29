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
];
