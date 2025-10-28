<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use App\Models\Hotel;
use App\Models\Profile;
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

        $user = User::create([
            'uuid' => (string) \Str::uuid(),
            'first_name' => 'Asif M.',
            'last_name' => 'Sazid',
            'title' => 'AsifMSazid',
            'role_id' => '3',
            'role_title' => 'Client',
            'email' => 'asif@gmail.com',
            'password' => Hash::make("1q2w3e4r"),
        ]);

        Profile::create([
            'uuid' => (string) \Str::uuid(),
            'title' => $user->first_name . ' ' . $user->last_name,
            'user_id' => $user->id,
            'user_uuid' => $user->uuid,
            'user_title' => $user->title
        ]);

        Country::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Bangladesh',
            'country_code' => 'BAN',
        ]);

        Country::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Thailand',
            'country_code' => 'THA',
        ]);

        City::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Dhaka',
            'country_id' => 1
        ]);

        City::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Chittagong',
            'country_id' => 1
        ]);

        Hotel::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Hotel Intercontinental Ltd',
            'city_id' => 1
        ]);
    }
}
