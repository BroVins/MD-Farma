<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $username = trim((string) config('admin.username', ''));
        $password = (string) config('admin.password', '');

        if ($username === '' || $password === '') {
            $this->command?->warn(
                'Admin tidak dibuat. Isi ADMIN_USERNAME dan ADMIN_PASSWORD di .env sebelum menjalankan AdminSeeder.'
            );

            return;
        }

        if (mb_strlen($password) < 12) {
            $this->command?->error(
                'ADMIN_PASSWORD minimal 12 karakter.'
            );

            return;
        }

        if (Admin::query()->count() > 1) {
            $this->command?->error(
                'Ditemukan lebih dari satu akun admin. Rapikan data admin secara manual sebelum menjalankan seeder.'
            );

            return;
        }

        $admin = Admin::query()->oldest('id')->first()
            ?? new Admin();

        $admin->forceFill([
            'username' => $username,
            'password' => Hash::make($password),
        ])->save();
    }
}
