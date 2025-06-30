<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Purchase extends Model
{
    use UsesTenantConnection;

    protected $fillable = ["reference_no", "user_id", "warehouse_id", "supplier_id", "item", "total_qty", "total_discount", "total_cashback", "total_tax", "total_cost", "order_tax_rate", "order_tax", "order_discount_type", "order_discount_value", "order_discount", "order_cashback_type", "order_cashback_value", "order_cashback", "shipping_cost", "grand_total", "paid_amount", "status", "payment_status", "document", "note", "is_po", "is_tempo", "created_at"];

    public function supplier()
    {
        return $this->belongsTo('App\Supplier');
    }

    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }

    public function products()
    {
        return $this->hasMany('App\ProductPurchase');
    }
}
