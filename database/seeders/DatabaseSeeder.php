<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\ActivityCategory;
use App\Models\City;
use App\Models\Country;
use App\Models\Currency;
use App\Models\Hotel;
use App\Models\Inclusion;
use App\Models\Navigation;
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

        City::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Pattaya',
            'country_id' => 2
        ]);

        City::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Phuket',
            'country_id' => 2
        ]);

        City::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Bangkok',
            'country_id' => 2
        ]);

        Currency::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Bangladeshi Taka',
            'currency_code' => 'BDT',
            'country_id' => 1,
            'icon' => '৳'
        ]);

        Currency::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Thai Baht',
            'currency_code' => 'THB',
            'country_id' => 2,
            'icon' => '฿'
        ]);

        Hotel::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Hotel Intercontinental Ltd',
            'city_id' => 1
        ]);

        Activity::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'City Tours',
            'country_id' => 1,
            'city_id' => 1,
            'currency_id' => 1
        ]);

        Activity::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'City Tours',
            'country_id' => 1,
            'city_id' => 2,
            'currency_id' => 1
        ]); 

        Activity::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'City Tours',
            'country_id' => 2,
            'city_id' => 1,
            'currency_id' => 2
        ]);

        Activity::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'City Tours',
            'country_id' => 2,
            'city_id' => 2,
            'currency_id' => 2
        ]);

        Activity::create([
            'uuid' => (string) \Illuminate\Support\Str::uuid(),
            'title' => 'Museum Visits',
            'country_id' => 1,
            'city_id' => 1,
            'currency_id' => 1
        ]);

        // Activity::create([
        //     'uuid' => (string) \Illuminate\Support\Str::uuid(),
        //     'title' => 'Adventure Sports',
        //     'activity_category_id' => 1
        // ]);

        // Activity::create([
        //     'uuid' => (string) \Illuminate\Support\Str::uuid(),
        //     'title' => 'Cooking Classes',
        //     'activity_category_id' => 1
        // ]);

        // Activity::create([
        //     'uuid' => (string) \Illuminate\Support\Str::uuid(),
        //     'title' => 'Wine Tasting',
        //     'activity_category_id' => 1
        // ]);

        // Activity::create([
        //     'uuid' => (string) \Illuminate\Support\Str::uuid(),
        //     'title' => 'Beach Activities',
        //     'activity_category_id' => 1
        // ]);



        Navigation::create([
            "uuid" => "46efcfb5-001c-41cd-a9d7-875ebd4f0484",
            "title" => "Dashboard",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/dashboard",
            "route" => "dashboard",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "5f6defae-8121-4a58-822e-d0e1a5c1d6a6",
            "title" => "Countries",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => null,
            "route" => null,
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "4b7ea3f8-3e13-4666-96a0-a39a8ef558be",
            "title" => "Country Lists",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/countries/index",
            "route" => "countries.index",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "3f08b976-d41d-407f-bff7-71d15e761895",
            "title" => "Create Country",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/countries/create",
            "route" => "countries.create",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "4ab9f403-2d14-4030-91a1-fe0e305e49de",
            "title" => "Cities",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => null,
            "route" => null,
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "ca2ebe70-55ae-409b-89f8-e0ca71324ce5",
            "title" => "City List",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/cities/index",
            "route" => "cities.index",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "4f793ce6-612f-4bb6-8a13-b4dc63a16017",
            "title" => "Create City",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/cities/create",
            "route" => "cities.create",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "5e273bbb-e885-4601-a931-250fde137eb3",
            "title" => "Hotels",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => null,
            "route" => null,
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "ae0e86ab-a36c-47ee-997a-10c1bfb398be",
            "title" => "Hotel List",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/hotels/index",
            "route" => "hotels.index",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "0c08e608-76a9-4b96-9ed1-46914735ec3d",
            "title" => "Create Hotel",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/hotels/create",
            "route" => "hotels.create",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "a5ffdd41-b45e-465f-a626-84f57b6ac5e3",
            "title" => "Navigations",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => null,
            "route" => null,
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "61517c2a-6f56-4f9e-a4cb-17dc82a55156",
            "title" => "Navigation Lists",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/navigations/index",
            "route" => "navigations.index",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "42a564a8-9bfe-4a1e-ad4c-a6dbb3b82f86",
            "title" => "Create Navigation",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "navigations/create",
            "route" => "navigations.create",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "9afa0e1c-6a3f-4465-855f-6e9235cf53f8",
            "title" => "Activities",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => null,
            "route" => null,
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "d718c48e-2108-4be0-bfa0-ee534d723279",
            "title" => "Activity Lists",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/activities/index",
            "route" => "activities.index",
            "navigation_for" => null,
        ]);

        Navigation::create([
            "uuid" => "8ce4e204-a3e1-46ef-85ae-7e6c794ae9ec",
            "title" => "Create Activity",
            "parent_id" => null,
            "nav_icon" => null,
            "created_by" => "1",
            "url" => "/activities/create",
            "route" => "activities.create",
            "navigation_for" => null,
        ]);
    }
}
