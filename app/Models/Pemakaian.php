<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemakaian extends Model
{
    // Nama tabel
    protected $table = 'pemakaians';

    // Kolom yang dapat diisi
    protected $fillable = [
        'tahun',
        'bulan',
        'NoKontrol',
        'meterawal',
        'meterakhir',
        'jumlahpakai',
        'biayabebanpemakai',
        'biayapemakaian',
        'status',
        'jumlahbayar',
    ];

    // Relasi ke model Pelanggan
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'NoKontrol', 'NoKontrol');
    }
}
