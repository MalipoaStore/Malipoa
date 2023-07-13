<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'samuel',
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'phone' => '+254795865385',
                'address' => 'Nairobi, Kenya',
                'password' => Hash::make('111'),
                'role' => 'admin',
                'status' => 'active'
                
            ],
            [
                'name' => 'Joseph',
                'username' => 'vendor',
                'email' => 'vendor@gmail.com',
                'phone' => '+254765862385',
                'address' => 'Meru, Kenya',
                'password' => Hash::make('111'),
                'role' => 'vendor',
                'status' => 'active'
                
            ],
            [
                'name' => 'Derick',
                'username' => 'user',
                'email' => 'user@gmail.com',
                'phone' => '+254776865265',
                'address' => 'Umoja 1, Nairobi, Kenya',
                'password' => Hash::make('111'),
                'role' => 'user',
                'status' => 'active'
                
            ]
        ]);
    }
}
