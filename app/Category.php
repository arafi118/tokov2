<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Category extends Model
{
    use UsesTenantConnection;
protected $fillable =[

        "name", 'image', "parent_id", "is_active"
    ];

    public function product()
    {
    	return $this->hasMany('App\Product');
    }
}
