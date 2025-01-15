<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OperationalCostTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $names = [
            'insurance',
            'photocopy',
            'rental',
            'electric',
            'membership_bar_council',
            'audit_fee',
            'employee_salary',
            'subscription_fee',
            'travelling_expenses',
            'office_supplies',
            'filing_fee',
            'postage_fee',
        ];

        foreach ($names as $name) {
            DB::table('operational_cost_types')->insert([
                'name' => $name,
            ]);
        }
    }
}
