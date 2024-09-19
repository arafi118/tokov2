<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Delivery extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "reference_no", "sale_id", "user_id", "address", "delivered_by", "recieved_by", "file", "status", "note"
    ];

    public function sale()
    {
    	return $this->belongsTo("App\Sale");
    }

    public function user()
    {
    	return $this->belongsTo("App\User");
    }
}
