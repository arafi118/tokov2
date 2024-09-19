<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Account extends Model
{
    use UsesTenantConnection;
    protected $fillable =[
        "account_no", "name", "initial_balance", "total_balance", "note", "is_default", "is_active"
    ];
}
