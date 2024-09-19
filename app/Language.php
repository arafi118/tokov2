<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Language extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "code"
    ];
}
