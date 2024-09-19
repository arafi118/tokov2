<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TbJurnalDetail extends Model
{
    use HasFactory, UsesTenantConnection;

    public function rek_debit()
    {
        return $this->belongsTo('App\TbRekening', 'debit_kode', 'kode');
    }

    public function rek_kredit()
    {
        return $this->belongsTo('App\TbRekening', 'kredit_kode', 'kode');
    }

    public function jenis_trx()
    {
        return $this->belongsTo(\App\TbJenisTransaksi::class, 'tb_jenis_transaksi_id', 'id');
    }
}
