<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
        
            ['barang_id' => 1, 'kategori_id' => 1, 'barang_kode' => 'BRG001', 'barang_nama' => 'Keripik Kentang', 'harga_beli' => 4500, 'harga_jual' => 7000],
            ['barang_id' => 2, 'kategori_id' => 1, 'barang_kode' => 'BRG002', 'barang_nama' => 'Donat Coklat', 'harga_beli' => 6000, 'harga_jual' => 8500],
            ['barang_id' => 3, 'kategori_id' => 2, 'barang_kode' => 'BRG003', 'barang_nama' => 'Susu Kotak', 'harga_beli' => 3500, 'harga_jual' => 5000],
            ['barang_id' => 4, 'kategori_id' => 2, 'barang_kode' => 'BRG004', 'barang_nama' => 'Mie Instan', 'harga_beli' => 4000, 'harga_jual' => 5500],
            ['barang_id' => 5, 'kategori_id' => 3, 'barang_kode' => 'BRG005', 'barang_nama' => 'Headset Bluetooth', 'harga_beli' => 120000, 'harga_jual' => 180000],
            ['barang_id' => 6, 'kategori_id' => 3, 'barang_kode' => 'BRG006', 'barang_nama' => 'Monitor LED', 'harga_beli' => 300000, 'harga_jual' => 450000],
            ['barang_id' => 7, 'kategori_id' => 4, 'barang_kode' => 'BRG007', 'barang_nama' => 'Keset Karet', 'harga_beli' => 18000, 'harga_jual' => 25000],
            ['barang_id' => 8, 'kategori_id' => 4, 'barang_kode' => 'BRG008', 'barang_nama' => 'Ember Plastik', 'harga_beli' => 25000, 'harga_jual' => 35000],
            ['barang_id' => 9, 'kategori_id' => 5, 'barang_kode' => 'BRG009', 'barang_nama' => 'Celana Jeans', 'harga_beli' => 60000, 'harga_jual' => 75000],
            ['barang_id' => 10, 'kategori_id' => 5, 'barang_kode' => 'BRG010', 'barang_nama' => 'Sweater Rajut', 'harga_beli' => 120000, 'harga_jual' => 160000],
        ];
        

        DB::table('m_barang')->insert($data);
    }
}