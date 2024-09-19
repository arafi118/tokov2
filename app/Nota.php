<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Nota extends Model
{
    use HasFactory,UsesTenantConnection;

    protected $fillable = ['jenis_transaksi','kode','table'];
}
