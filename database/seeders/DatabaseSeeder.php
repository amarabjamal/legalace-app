<?php

namespace Database\Seeders;

use App\Models\Clerk;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Company;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\User::factory(100)->create();
        // Clerk::factory(90)->create();
        Company::factory(10)->create();
        User::factory(10)->create();

        $roles = [
            'admin',
            'lawyer'
        ];

        foreach($roles as $role) {
            Role::factory()->create(['name' => $role]);
        }
        // Role::factory()->create([
        //     'name' => 'Admin'
        // ]);
        // Role::factory()->create([
        //     'name'=>'Lawyer'
        // ]);

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
