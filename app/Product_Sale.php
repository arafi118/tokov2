<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Product_Sale extends Model
{
    protected $table = 'product_sales';
    use UsesTenantConnection;
    protected $fillable = [
        "sale_id", "product_id", "product_batch_id", "variant_id", 'imei_number', "qty", "sale_unit_id", "net_unit_price", "discount", "cashback", "tax_rate", "tax", "total"
    ];
}
