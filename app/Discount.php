<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Discount extends Model
{
    use HasFactory;

    use UsesTenantConnection;
protected $fillable= ['name', 'applicable_for', 'product_list', 'valid_from', 'valid_till', 'type', 'value', 'minimum_qty', 'maximum_qty', 'days', 'is_active'];

    public function discountPlans()
    {
        return $this->belongsToMany('App\DiscountPlan', 'discount_plan_discounts');
    }
}
