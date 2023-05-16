<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'roles-list',
                'display_name' => 'Daftar Hak Akses',
                'description' => 'Daftar Hak Akses',
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'roles-create',
                'display_name' => 'Tambah Hak Akses',
                'description' => 'Tambah Hak Akses',
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'roles-edit',
                'display_name' => 'Ubah Hak Akses',
                'description' => 'Ubah Hak Akses',
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'roles-delete',
                'display_name' => 'Hapus Hak Akses',
                'description' => 'Hapus Hak Akses',
                'sort' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'name' => 'users-list',
                'display_name' => 'Daftar Pengguna',
                'description' => 'Daftar Pengguna',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'name' => 'users-create',
                'display_name' => 'Tambah Pengguna',
                'description' => 'Tambah Pengguna',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 7,
                'name' => 'users-edit',
                'display_name' => 'Ubah Pengguna',
                'description' => 'Ubah Pengguna',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'name' => 'users-delete',
                'display_name' => 'Hapus Pengguna',
                'description' => 'Hapus Pengguna',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 9,
                'name' => 'room-type-list',
                'display_name' => 'Daftar Tipe Ruangan',
                'description' => 'Daftar Tipe Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 10,
                'name' => 'room-type-create',
                'display_name' => 'Tambah Tipe Ruangan',
                'description' => 'Tambah Tipe Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 11,
                'name' => 'room-type-edit',
                'display_name' => 'Ubah Tipe Ruangan',
                'description' => 'Ubah Tipe Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 12,
                'name' => 'room-type-delete',
                'display_name' => 'Hapus Tipe Ruangan',
                'description' => 'Hapus Tipe Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 13,
                'name' => 'facility-list',
                'display_name' => 'Daftar Fasilitas',
                'description' => 'Daftar Fasilitas',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 14,
                'name' => 'facility-create',
                'display_name' => 'Tambah Fasilitas',
                'description' => 'Tambah Fasilitas',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 15,
                'name' => 'facility-edit',
                'display_name' => 'Ubah Fasilitas',
                'description' => 'Ubah Fasilitas',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 16,
                'name' => 'facility-delete',
                'display_name' => 'Hapus Fasilitas',
                'description' => 'Hapus Fasilitas',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 17,
                'name' => 'extra-charge-list',
                'display_name' => 'Daftar Biaya Tambahan',
                'description' => 'Daftar Biaya Tambahan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 18,
                'name' => 'extra-charge-create',
                'display_name' => 'Tambah Biaya Tambahan',
                'description' => 'Tambah Biaya Tambahan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 19,
                'name' => 'extra-charge-edit',
                'display_name' => 'Ubah Biaya Tambahan',
                'description' => 'Ubah Biaya Tambahan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 20,
                'name' => 'extra-charge-delete',
                'display_name' => 'Hapus Biaya Tambahan',
                'description' => 'Hapus Biaya Tambahan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 21,
                'name' => 'room-list',
                'display_name' => 'Daftar Ruangan',
                'description' => 'Daftar FasilitRuanganas',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 22,
                'name' => 'room-create',
                'display_name' => 'Tambah Ruangan',
                'description' => 'Tambah Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 23,
                'name' => 'room-edit',
                'display_name' => 'Ubah Ruangan',
                'description' => 'Ubah Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 24,
                'name' => 'room-delete',
                'display_name' => 'Hapus Ruangan',
                'description' => 'Hapus Ruangan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 25,
                'name' => 'transaction-list',
                'display_name' => 'Daftar Penjualan',
                'description' => 'Daftar Penjualan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 26,
                'name' => 'transaction-create',
                'display_name' => 'Tambah Penjualan',
                'description' => 'Tambah Penjualan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 27,
                'name' => 'transaction-edit',
                'display_name' => 'Ubah Penjualan',
                'description' => 'Ubah Penjualan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 28,
                'name' => 'transaction-delete',
                'display_name' => 'Hapus Penjualan',
                'description' => 'Hapus Penjualan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 29,
                'name' => 'report-list',
                'display_name' => 'Laporan',
                'description' => 'Laporan',
                'sort' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
            // add new permissions here 
        ]);
    }
}
