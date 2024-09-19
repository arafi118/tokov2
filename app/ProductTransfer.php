<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ProductTransfer extends Model
{
    protected $table = 'product_transfer';
    use UsesTenantConnection;
protected $fillable =[

        "transfer_id", "product_id", "product_batch_id", "variant_id", "imei_number", "qty", "purchase_unit_id", "net_unit_cost", "tax_rate", "tax", "total"
    ];
}
