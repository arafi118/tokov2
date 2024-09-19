<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class DiscountPlanCustomer extends Model
{
    use HasFactory;

    use UsesTenantConnection;
protected $fillable = ['discount_plan_id', 'customer_id'];
}
