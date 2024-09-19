<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentWithPaypal extends Model
{
    protected $table = 'payment_with_paypal';
    use UsesTenantConnection;
protected $fillable =[
        "payment_id", "transaction_id"
    ];
}
