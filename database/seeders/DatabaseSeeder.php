<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'name' => 'Administrator Assetera',
                'email' => 'admin@assetera.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        User::updateOrCreate(
            ['username' => 'pimpinan'],
            [
                'name' => 'Pimpinan Assetera',
                'email' => 'pimpinan@assetera.com',
                'role' => 'pimpinan',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        User::updateOrCreate(
            ['username' => 'staff'],
            [
                'name' => 'Staff Gudang Assetera',
                'email' => 'staff@assetera.com',
                'role' => 'staff',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
            ]
        );

        $this->command->info('Creating 50 Assets...');
        \App\Models\Asset::factory(50)->create();
        
        $this->command->info('Creating 30 Transactions...');
        \App\Models\Transaction::factory(30)->create();
        
        $this->command->info('Creating 15 Maintenances...');
        \App\Models\Maintenance::factory(15)->create();
    }
}
