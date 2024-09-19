<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Product_Supplier extends Model
{
	protected $table = 'product_supplier';
    use UsesTenantConnection;
protected $fillable =[

        "product_code", "supplier_id", "qty", "price"
    ];
}
