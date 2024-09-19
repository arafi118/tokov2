<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Permission extends Model
{
    use HasFactory, UsesTenantConnection;

    public function role()
    {
        return $this->hasOne(RoleHasPermission::class);
    }
}
