<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentWithGiftCard extends Model
{
    protected $table = 'payment_with_gift_card';
    use UsesTenantConnection;
protected $fillable =[
        "payment_id", "gift_card_id"
    ];
}
