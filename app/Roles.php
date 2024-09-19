<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Roles extends Model
{
    use UsesTenantConnection;
    protected $fillable =[
        "name", "description", "guard_name", "is_active"
    ];
}
