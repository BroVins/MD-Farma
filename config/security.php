<?php

return [
    /*
     * HSTS hanya dikirim ketika aplikasi berjalan pada environment
     * production dan request menggunakan HTTPS. Jangan aktifkan pada domain
     * yang belum sepenuhnya siap dilayani melalui HTTPS.
     */
    'hsts_enabled' => (bool) env(
        'SECURITY_HSTS_ENABLED',
        true
    ),

    'hsts_max_age' => (int) env(
        'SECURITY_HSTS_MAX_AGE',
        31536000
    ),
];
