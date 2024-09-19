<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Deposit extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "amount", "customer_id", "user_id", "note"
    ];
}
