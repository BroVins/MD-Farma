<?php

return [
    /*
     * Digunakan hanya oleh AdminSeeder untuk membuat atau memperbarui
     * akun admin tunggal. Jangan menyimpan nilai rahasia di repository.
     */
    'username' => env('ADMIN_USERNAME'),
    'password' => env('ADMIN_PASSWORD'),
];
