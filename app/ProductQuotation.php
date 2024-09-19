<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductQuotation extends Model
{
    protected $table = 'product_quotation';
    use UsesTenantConnection;
protected $fillable =[
        "quotation_id", "product_id", "product_batch_id", "variant_id", "qty", "sale_unit_id", "net_unit_price", "discount", "tax_rate", "tax", "total"
    ];
}
