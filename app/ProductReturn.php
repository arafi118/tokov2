<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductReturn extends Model
{
    protected $table = 'product_returns';
    use UsesTenantConnection;
    protected $fillable = [
        "return_id", "product_id", "variant_id", "imei_number", "product_batch_id", "qty", "sale_unit_id", "net_unit_price", "discount", "cashback", "tax_rate", "tax", "total"
    ];
}
