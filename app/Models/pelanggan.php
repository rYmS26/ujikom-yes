<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggans';

    protected $fillable = [
        'NoKontrol',
        'nama',
        'alamat',
        'telepon',
        'jenis_plg',
    ];

    public function jenisPlg()
    {
        return $this->belongsTo(JenisPlg::class, 'jenis_plg', 'id_jenis');
    }
    public function jenis()
{
    return $this->belongsTo(JenisPlg::class, 'id_jenis_plg');
}

}
