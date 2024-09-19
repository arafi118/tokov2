<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductAdjustment extends Model
{
    protected $table = 'product_adjustments';
    use UsesTenantConnection;
protected $fillable =[
        "adjustment_id", "product_id", "variant_id", "qty", "action"
    ];
}
