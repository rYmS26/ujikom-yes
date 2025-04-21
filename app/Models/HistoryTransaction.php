<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryTransaction extends Model
{
    protected $table = 'histori_pembayaran';

    protected $fillable = [
        'pelanggan_id',
        'transaksi_id',
        'tanggal',
        'total_bayar',
        'keterangan',
    ];
}
