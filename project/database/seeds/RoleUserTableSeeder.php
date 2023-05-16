<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('role_user')->insert([
            [
                'role_id' => 1,
                'user_id' => 'dd7cd604-d317-11ed-afa1-0242ac120002',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'user_id' => 'dd7cd604-d317-11ed-afa1-0242ac120003',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'user_id' => 'dd7cd604-d317-11ed-afa1-0242ac120004',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 4,
                'user_id' => 'dd7cd604-d317-11ed-afa1-0242ac120005',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);


    }
}
