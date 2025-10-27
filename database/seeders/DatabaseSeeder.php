<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
        // User::factory(10)->create();

        Role::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Super Admin',
        ]);

        Role::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Admin',
        ]);

        Role::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Client',
        ]);

        User::create([
            'first_name' => 'Asif M.',
            'last_name' => 'Sazid',
            'title' => 'AsifMSazid',
            'email' => 'asif@gmail.com',
            'password' => Hash::make("12341234"),
        ]);
    }
}
