<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Currency extends Model
{
    use UsesTenantConnection;
protected $fillable = ["name", "code", "exchange_rate"];
}
