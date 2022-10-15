<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'Customer',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'User',
            'guard_name' => 'api',
        ]);

        DB::table('roles')->insert([
            'name' => 'Admin',
            'guard_name' => 'api',
        ]);
    }
}
