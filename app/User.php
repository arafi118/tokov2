<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class User extends Authenticatable
{
    use Notifiable;
    use HasRoles;

    use UsesTenantConnection;
    protected $fillable = [
        'name', 'email','initial','fullname', 'password',"phone","company_name", "role_id", "biller_id", "warehouse_id", "is_active", "is_deleted"
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isActive()
    {
        return $this->is_active;
    }

    public function holiday() {
        return $this->hasMany('App\Holiday');
    }
}
