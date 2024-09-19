<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentWithDebitCard extends Model
{
    use HasFactory;

    use UsesTenantConnection;
protected $fillable =[

        "payment_id", "no_rekening","atas_nama_rekening"
    ];
}
