<?php

namespace Database\Seeders;

use \Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => '402446eb-ba54-4425-b8a4-7ec814b862ff',
            'name' => 'Admin',
            'email' => 'admin@fake.mail',
            'password' => Hash::make('admin123'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
