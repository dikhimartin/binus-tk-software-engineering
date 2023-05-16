<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('product_types')->insert([
            [
                'id' => Str::uuid(),
                'name' => 'Makanan',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Minuman',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Produk Segar',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Ibu & Anak',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Kesehatan & Kecantikan',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Home & Living',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Gadget & Elektronik',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Fashion',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'name' => '18+',
                'description' => 'Lorem ipsum dolor sit amet',
                'status' => 0,
                'sort' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);        
    }

}