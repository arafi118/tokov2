<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class RewardPointSetting extends Model
{
    use UsesTenantConnection;
protected $fillable = ["per_point_amount", "minimum_amount", "duration", "type", "is_active"];
}
