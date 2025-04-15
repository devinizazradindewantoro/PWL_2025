<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'kategori_kode' => 'KTN001', 'kategori_nama' => 'Alat Tulis'],
            ['kategori_id' => 2, 'kategori_kode' => 'KTN002', 'kategori_nama' => 'Pakaian'],
            ['kategori_id' => 3, 'kategori_kode' => 'KTN003', 'kategori_nama' => 'Peralatan Rumah'],
            ['kategori_id' => 4, 'kategori_kode' => 'KTN004', 'kategori_nama' => 'Makanan'],
            ['kategori_id' => 5, 'kategori_kode' => 'KTN005', 'kategori_nama' => 'Minuman'],
        ];

        DB::table('m_kategori')->insert($data);
    }
}