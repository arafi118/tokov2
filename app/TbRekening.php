<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TbRekening extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    protected $fillable = ['kode', 'nama', 'slug', 'parent_id', 'depth', 'jenis_mutasi'];

    public function children()
    {
        return $this->hasMany('App\TbRekening', 'parent_id');
    }

    public function saldo()
    {
        return $this->hasMany('App\TbRekeningSaldo', 'tb_rekening_kode', 'kode');
    }

    public function komsaldo()
    {
        return $this->hasMany('App\TbRekeningSaldo', 'tb_rekening_kode', 'kode');
    }

    public function getRekening()
    {
        $query = TbRekening::selectRaw('tb_rekenings.*, debit.rekening_debit_kode as rekening_debit_kode,kredit.rekening_kredit_kode as rekening_kredit_kode')
            ->leftJoin('tb_konfigurasi_transaksis as debit', 'debit.rekening_debit_kode', '=', 'tb_rekenings.kode')
            ->leftJoin('tb_konfigurasi_transaksis as kredit', 'kredit.rekening_kredit_kode', '=', 'tb_rekenings.kode')
            ->whereNotNull('no_rek_bank')
            ->whereNotNull('debit.rekening_debit_kode')
            ->whereNotNull('kredit.rekening_kredit_kode')
            ->groupBy('tb_rekenings.kode')
            ->get();

        return $query;
    }
}
