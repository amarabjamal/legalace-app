<?php

namespace Database\Seeders;

use App\Models\Clerk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Client;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Company::factory(10)->create();
        User::factory(10)->create();

        $roles = [
            'admin',
            'lawyer'
        ];

        foreach($roles as $role) {
            Role::factory()->create(['name' => $role]);
        }

        $id_types = [
            [
                'name' =>'Malaysian Identification Card',
                'slug' => 'myic'
            ],
            [
                'name' =>'Passport',
                'slug' => 'passport'
            ],
        ];

        $user_role = [
            [
                'user_id' => 1,
                'role_id' => 1
            ],
            [
                'user_id' => 2,
                'role_id' => 2
            ]
        ];

        DB::table('user_role')->insert($user_role);

        DB::table('id_types')->insert($id_types);
        Client::factory(10)->create();
    }
}
