<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TbKonfigurasiTransaksi extends Model
{
    use HasFactory;
    use UsesTenantConnection;
protected $fillable = ['tb_jenis_transaksi_id','cara_bayar','rekening_debit_id','rekening_kredit_id']; 
}
