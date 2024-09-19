<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentWithCreditCard extends Model
{
    protected $table = 'payment_with_credit_card';

    use UsesTenantConnection;
protected $fillable =[

        "payment_id", "customer_id", "customer_stripe_id", "charge_id"
    ];
}
