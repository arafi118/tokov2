<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class HrmSetting extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "checkin", "checkout"
    ];
}
