<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasTenant;

class BarangMasuk extends Model
{
    use HasFactory, HasTenant;

    protected $table = 'barangmasuks';

    protected $fillable = [
        'id_barangmasuk',
        'tanggal',
        'id_databarang',
        'id_tenant',
        'jumlah_masuk',
    ];

    public function dataBarang()
    {
        return $this->belongsTo(DataBarang::class, 'id_databarang');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'id_tenant');
    }
}
