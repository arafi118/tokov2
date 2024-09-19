<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class DiscountPlan extends Model
{
    use HasFactory;

    use UsesTenantConnection;
protected $fillable = ['name', 'is_active'];

    public function customers()
    {
        return $this->belongsToMany('App\Customer', 'discount_plan_customers');
    }
}
