<?php

namespace App\Models;

use App\Models\Kota;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kota_id',
        'pelanggan',
        'total',
        'tanggal',
        'status',
    ];

    public function kota()
    {
        return $this->belongsTo(Kota::class);
    }
}
