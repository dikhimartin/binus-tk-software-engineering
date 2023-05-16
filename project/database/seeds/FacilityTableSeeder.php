<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FacilityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('facilities')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'AC',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Wifi',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'TV',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Bathtub',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Meja',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pancuran',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pancuran dan bathtub terpisah',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Pengering rambut',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Jubah mandi',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Brankas kamar',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Kulkas',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Sarapan prasmanan',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Area merokok',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 13,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);    
    }
}
