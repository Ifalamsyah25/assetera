<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator DapurMBG',
            'username' => 'admin',
            'email' => 'admin@dapurmbg.test',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Pimpinan DapurMBG',
            'username' => 'pimpinan',
            'email' => 'pimpinan@dapurmbg.test',
            'role' => 'pimpinan',
        ]);

        User::factory()->create([
            'name' => 'Staff Gudang',
            'username' => 'staff',
            'email' => 'staff@dapurmbg.test',
            'role' => 'staff',
        ]);
    }
}
