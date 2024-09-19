<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Expense extends Model
{
    use UsesTenantConnection;
protected $fillable =[
        "reference_no", "expense_category_id", "warehouse_id", "account_id", "user_id", "cash_register_id", "amount", "note", "created_at"  
    ];

    public function warehouse()
    {
    	return $this->belongsTo('App\Warehouse');
    }

    public function expenseCategory() {
    	return $this->belongsTo('App\ExpenseCategory');
    }
}
