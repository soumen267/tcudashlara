<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'role' => 'admin',
                'email' => 'admin@admin.com',
                'password' => bcrypt('admin@2024'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ],
            [
                'name' => 'Super Admin',
                'role' => 'superadmin',
                'email' => 'superadmin@admin.com',
                'password' => bcrypt('superadmin@2024'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        ]
    );
    }
}
