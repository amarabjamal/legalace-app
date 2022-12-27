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

        $accountTypes = [
            [
                'name' => 'Client Account',
                'slug' => 'clientaccount',
            ],
            [
                'name' => 'Firm Account',
                'slug' => 'firmaccount',
            ],
        ];

        $bankAccounts = [
            [
                'account_name' => 'AC Partnership',
                'bank_name' => 'Maybank Berhad',
                'account_number' => '167239581253',
                'bank_address' => 'Maybank@UM, Universiti Malaya, 50603 Kuala Lumpur, WP Kuala Lumpur',
                'swift_code' => 'MBBEMYKLXXX',
                'account_type' => 1,
                'label' => 'Client Account 1',
                'created_by' => 1,
                'company_id' => 1,
            ],
        ];

        DB::table('user_role')->insert($user_role);

        DB::table('account_types')->insert($accountTypes);

        DB::table('bank_accounts')->insert($bankAccounts);

        DB::table('id_types')->insert($id_types);
        Client::factory(10)->create();
    }
}
