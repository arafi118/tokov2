<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class TbIndukJenisTransaksi extends Model
{
    use HasFactory;
    use UsesTenantConnection;
    protected $fillable = ['nama', 'slug'];

    public function details()
    {
        return $this->hasMany('App\TbJenisTransaksi');
    }

    public function jenisTransaksi()
    {
        return $this->hasMany(TbJenisTransaksi::class);
    }
}
