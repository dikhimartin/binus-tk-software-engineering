<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RoomTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('room_types')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Standard Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Superior Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Deluxe Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Twin Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Single Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Double Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Family Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Junior Suite Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Suite Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Presidential Suite',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Connecting Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Disabled Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Smoking Room',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);     
    }
}
