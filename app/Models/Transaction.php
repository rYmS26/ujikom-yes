<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'pelanggan_id',
        'petugas_id',
        'tanggal',
        'bulan_tagihan',
        'jumlah_tagihan',
        'jumlah_bayar',
        'metode_pembayaran',
        'catatan',
    ];
}
