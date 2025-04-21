<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisPlg extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'jenis_plg';

    // Explicitly set the primary key
    protected $primaryKey = 'id';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'id_jenis',
        'nama_jenis',
        'biayabeban',
        'tarifkwh',
    ];

    // Enable timestamps
    public $timestamps = true;

    /**
     * Define a relationship to the Pelanggan model.
     */
    public function pelanggan()
    {
        return $this->hasMany(Pelanggan::class, 'id_jenis', 'id_jenis');
    }
    public function tarifLogs()
{
    return $this->hasMany(TarifLog::class, 'id_jenis', 'id_jenis');
}

}
