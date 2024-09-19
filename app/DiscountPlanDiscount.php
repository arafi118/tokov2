<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class DiscountPlanDiscount extends Model
{
    use HasFactory;

    use UsesTenantConnection;
protected $fillable =['discount_plan_id', 'discount_id'];
}
