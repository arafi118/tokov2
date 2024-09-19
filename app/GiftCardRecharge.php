<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class GiftCardRecharge extends Model
{
    protected $table = 'gift_card_recharges';

    use UsesTenantConnection;
protected $fillable =[

        "gift_card_id", "amount", "user_id"
    ];
}
