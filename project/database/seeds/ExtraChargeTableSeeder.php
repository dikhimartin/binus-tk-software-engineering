<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ExtraChargeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('extra_charges')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Minuman soda',
                'description' => 'Lorem ipsum dolor sit amet',
                'price' => 20000,
                'status' => 0,
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Air Putih',
                'description' => 'Lorem ipsum dolor sit amet',
                'price' => 15000,
                'status' => 0,
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Jasa Laundry',
                'description' => 'Lorem ipsum dolor sit amet',
                'price' => 100000,
                'status' => 0,
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Snack',
                'description' => 'Lorem ipsum dolor sit amet',
                'price' => 25000,
                'status' => 0,
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);   
    }
}
