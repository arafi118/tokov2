<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TbRekeningSaldo extends Model
{
    use HasFactory, UsesTenantConnection;

    public function getSaldoAwalTahunLalu($kd_rekening, $tahun, $warehouse_id)
    {
        $saldo = SELF::selectRaw('COALESCE(debit_12,0) as debit,COALESCE(kredit_12,0) as kredit')
            ->where('tb_rekening_kode', $kd_rekening)
            ->where('tahun', $tahun)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        return $saldo;
    }

    public function getSaldoBulanLalu($kd_rekening, $tahun, $bulan, $warehouse_id)
    {
        $saldo = SELF::selectRaw('debit_' . $bulan . ' as debit,kredit_' . $bulan . ' as kredit')
            ->where('tb_rekening_kode', $kd_rekening)
            ->where('tahun', $tahun)
            ->where('warehouse_id', $warehouse_id)
            ->first();

        return $saldo;
    }

    public function trx_debit()
    {
        return $this->hasOne(\App\TbJurnalDetail::class, 'debit_kode', 'tb_rekening_kode');
    }

    public function trx_kredit()
    {
        return $this->hasOne(\App\TbJurnalDetail::class, 'kredit_kode', 'tb_rekening_kode');
    }
}
