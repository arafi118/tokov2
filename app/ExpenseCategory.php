<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ExpenseCategory extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "code", "name", "is_active"  
    ];

    public function expense() {
    	return $this->hasMany('App\Expense');
    }
}
