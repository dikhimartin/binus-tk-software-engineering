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
                'name' => 'Single',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Double',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Suite',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Twin',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Executive',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Deluxe',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Family',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Connecting',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Penthouse',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Bungalow',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Smoking',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);     
    }
}
