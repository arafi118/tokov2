<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Supplier extends Model
{
    use UsesTenantConnection;
protected $fillable =[

        "name", "image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country", "is_active"
        
    ];

    public function product()
    {
    	return $this->hasMany('App/Product');
    	
    }
}
