<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class RoleHasPermission extends Model
{
    use HasFactory, UsesTenantConnection;
    protected $table = 'role_has_permissions';
}
