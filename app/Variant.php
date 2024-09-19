<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Variant extends Model
{
    use UsesTenantConnection;
protected $fillable = ['name'];

    public function product()
    {
    	return $this->belongsToMany('App\Variant', 'product_variants');
    }
}
