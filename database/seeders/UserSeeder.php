<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Akun staf & customer default.
     * Format: [nama, email, role]
     */
    private const STAFF = [
        ['Admin Utama', 'admin@tanjungjaya.com', 'Admin'],
        ['Manager Operasional', 'manager@tanjungjaya.com', 'Manager'],
        ['Kepala Gudang', 'gudang@tanjungjaya.com', 'Gudang'],
        ['Customer Setia', 'customer@tanjungjaya.com', 'Customer'],
    ];

    /**
     * Idempoten: akun dicocokkan berdasarkan email dan password hanya
     * di-set saat akun baru dibuat, sehingga menjalankan ulang seeder
     * tidak menimpa password yang mungkin sudah diubah saat pengujian.
     */
    public function run(): void
    {
        foreach (self::STAFF as [$name, $email, $role]) {
            User::firstOrCreate(
                ['email' => $email],
                ['name' => $name, 'password' => Hash::make('password'), 'role' => $role]
            );
        }

        // Customer tambahan hanya dibuat bila populasinya masih kurang.
        $needed = 10 - User::where('role', 'Customer')->whereNotIn('email', array_column(self::STAFF, 1))->count();

        if ($needed > 0) {
            User::factory($needed)->create(['role' => 'Customer']);
        }
    }
}
