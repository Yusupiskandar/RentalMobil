<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kendaraan extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kendaraan',
        'description',
        'thumbnail',
        'gambar_kendaraan',
        'harga_kendaraan',
        'lama_sewa',
    ];

    protected $casts = [
        'gambar_kendaraan' => 'array',
        'harga_kendaraan' => 'integer',
    ];
}
