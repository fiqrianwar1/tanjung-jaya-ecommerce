<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        User::create(['name' => 'Admin Utama', 'email' => 'admin@tanjungjaya.com', 'password' => $password, 'role' => 'Admin']);
        User::create(['name' => 'Manager Operasional', 'email' => 'manager@tanjungjaya.com', 'password' => $password, 'role' => 'Manager']);
        User::create(['name' => 'Kepala Gudang', 'email' => 'gudang@tanjungjaya.com', 'password' => $password, 'role' => 'Gudang']);
        User::create(['name' => 'Customer Setia', 'email' => 'customer@tanjungjaya.com', 'password' => $password, 'role' => 'Customer']);

        User::factory(10)->create(['role' => 'Customer']);
    }
}
