<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductBatch extends Model
{
    use UsesTenantConnection;
protected $fillable = ["product_id", "batch_no", "expired_date", "qty"];
}
