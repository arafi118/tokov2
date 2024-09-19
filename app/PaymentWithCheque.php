<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentWithCheque extends Model
{
    protected $table = 'payment_with_cheque';

    use UsesTenantConnection;
protected $fillable =[

        "payment_id", "cheque_no"
    ];
}
