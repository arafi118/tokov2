<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Payroll extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "reference_no", "employee_id", "account_id", "user_id",
        "amount", "paying_method", "note"
    ];

    public function employee()
    {
    	return $this->belongsTo('App\Employee');
    }
}
