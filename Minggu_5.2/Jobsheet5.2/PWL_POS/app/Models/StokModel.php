<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokModel extends Model
{
    use HasFactory;
    protected $table = 't_stok'; // Sesuaikan dengan nama tabel di database
    protected $primaryKey = 'stok_id'; // Sesuaikan dengan primary key tabel

    protected $fillable = [
        'stok_tanggal',
        'stok_jumlah',
    ];

    // Relasi ke tabel barang (m_barang)
    public function barang()
    {
        return $this->belongsTo(BarangModel::class, 'barang_id', 'barang_id');
    }

    // Relasi ke tabel user (m_user)
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'user_id');
    }
}