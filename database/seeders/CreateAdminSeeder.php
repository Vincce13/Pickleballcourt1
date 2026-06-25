<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@szam.com'],
            [
                'name'     => 'SZAMCOURT',
                'email'    => 'admin@szam.com',
                'password' => Hash::make('szam2026'),
            ]
        );

        $this->command->info('Admin account created:');
        $this->command->info('  Email:    admin@szam.com');
        $this->command->info('  Password: szam2026');
    }
}