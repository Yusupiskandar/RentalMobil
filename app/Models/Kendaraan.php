<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
