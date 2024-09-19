<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Adjustment extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "reference_no", "warehouse_id", "document", "total_qty", "item", 
         "note"   
    ];
}
