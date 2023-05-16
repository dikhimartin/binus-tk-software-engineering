<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 'dd7cd604-d317-11ed-afa1-0242ac120002',
                'role_id' => 1,
                'username' => 'superadmin',
                'name' => 'Superadmin',
                'password' => Hash::make('superadmin'),
                'email' => 'superadmin@mail.com',
                'status' => 0,
                'additional' => null,
                'remember_token' => null,
            ],
            [
                'id' => 'dd7cd604-d317-11ed-afa1-0242ac120003',
                'role_id' => 2,
                'username' => 'admin',
                'name' => 'Admin',
                'password' => Hash::make('admin'),
                'email' => 'admin@mail.com',
                'status' => 0,
                'additional' => null,
                'remember_token' => null,
            ],
            [
                'id' => 'dd7cd604-d317-11ed-afa1-0242ac120004',
                'role_id' => 3,
                'username' => 'user',
                'name' => 'User',
                'password' => Hash::make('user'),
                'email' => 'user@mail.com',
                'status' => 0,
                'additional' => null,
                'remember_token' => null,
            ]
        ]);
    }
}
