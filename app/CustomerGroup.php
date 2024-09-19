<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CustomerGroup extends Model
{
    use UsesTenantConnection;
protected $fillable =[

        "name", "percentage", "is_active"
    ];
}
