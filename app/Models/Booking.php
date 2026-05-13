<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kendaraan_id',
        'nama_customer',
        'nik',
        'no_hp',
        'alamat',
        'foto_ktp',
        'foto_kk',
        'foto_sim',
        'foto_dokumentasi',
        'waktu_mulai',
        'waktu_selesai',
        'harga_rental',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'harga_rental' => 'integer',
    ];

    public function kendaraan(): BelongsTo
    {
        return $this->belongsTo(Kendaraan::class);
    }

    /**
     * True if another booking for this kendaraan overlaps [start, end].
     * Touching endpoints (end == other start) are not considered overlap.
     */
    public static function overlapsForKendaraan(int $kendaraanId, mixed $start, mixed $end, ?int $exceptBookingId = null): bool
    {
        $start = $start instanceof \DateTimeInterface ? $start : new \DateTimeImmutable((string) $start);
        $end = $end instanceof \DateTimeInterface ? $end : new \DateTimeImmutable((string) $end);

        $query = static::query()
            ->where('kendaraan_id', $kendaraanId)
            ->where('waktu_mulai', '<', $end)
            ->where('waktu_selesai', '>', $start);

        if ($exceptBookingId !== null) {
            $query->whereKeyNot($exceptBookingId);
        }

        return $query->exists();
    }
}
