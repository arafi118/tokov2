<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductPurchase extends Model
{
    protected $table = 'product_purchases';
    use UsesTenantConnection;
    
    protected $fillable =[

        "purchase_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "recieved", "purchase_unit_id", "net_unit_cost", "discount", "tax_rate", "tax", "total","hpp"
    ];
}
