<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Holiday extends Model
{
    use UsesTenantConnection;
protected $fillable = ["user_id", "from_date", "to_date", "note", "is_approved"];

    public static function createHoliday($data) {
    	Holiday::create($data);
    }

    public function user() {
    	return $this->belongsTo('App\User');
    }
}
