<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Sale extends Model
{
    use UsesTenantConnection;
    protected $fillable = [
        "reference_no", "user_id", "cash_register_id", "customer_id", "warehouse_id", "biller_id", "item", "total_qty", "total_discount", "total_cashback", "total_tax", "total_price", "order_tax_rate", "order_tax", "order_discount_type", "order_discount_value", "order_discount", "order_cashback_type", "order_cashback_value", "order_cashback", "coupon_id", "coupon_discount", "shipping_cost", "grand_total", "sale_status", "payment_status", "paid_amount", "document", "sale_note", "staff_note", "is_po", "is_tempo", "created_at"
    ];

    public function biller()
    {
        return $this->belongsTo('App\Biller');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->hasMany('App\Product_Sale');
    }
}
