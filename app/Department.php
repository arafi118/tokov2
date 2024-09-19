<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Department extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "name", "is_active"
    ];
}
