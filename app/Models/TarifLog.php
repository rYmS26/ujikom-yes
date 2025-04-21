<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TarifLog extends Model
{
    protected $table = 'tarif_log';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'id_jenis',
        'tarifkwh',
        'biayabeban',
        'berlaku_mulai',
        'berlaku_sampai',
    ];

    public function jenisPlg()
    {
        return $this->belongsTo(JenisPlg::class, 'id_jenis', 'id_jenis');
    }
    
}
